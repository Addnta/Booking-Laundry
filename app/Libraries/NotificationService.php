<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\UserModel;
use Config\Email;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

class NotificationService
{
    protected NotificationModel $notificationModel;
    protected UserModel $userModel;
    protected Email $emailConfig;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->emailConfig = new Email();
    }

    public function notify(string|int $userId, string $type, string $title, string $message, ?string $bookingCode = null): bool
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return false;
        }

        $this->notificationModel->save([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (empty($user['email'])) {
            return false;
        }

        return $this->sendEmail(
            $user['email'],
            $title,
            $this->buildEmailBody($user, $title, $message, $bookingCode)
        );
    }

    public function notifyAdmins(string $type, string $title, string $message, ?string $bookingCode = null): void
    {
        $admins = $this->userModel
            ->where('role', 'admin')
            ->where('status', 'active')
            ->findAll();

        foreach ($admins as $admin) {
            $this->notificationModel->save([
                'user_id' => $admin['id'],
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!empty($admin['email'])) {
                $this->sendEmail(
                    $admin['email'],
                    $title,
                    $this->buildEmailBody($admin, $title, $message, $bookingCode)
                );
            }
        }
    }

    protected function buildEmailBody(array $user, string $title, string $message, ?string $bookingCode = null): string
    {
        return view('emails/payment_notification', [
            'title' => $title,
            'message' => $message,
            'user' => $user,
            'bookingCode' => $bookingCode,
        ]);
    }

    protected function sendEmail(string $to, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = (string) $this->emailConfig->SMTPHost;
            $mail->SMTPAuth = true;
            $mail->Username = (string) $this->emailConfig->SMTPUser;
            $mail->Password = (string) $this->emailConfig->SMTPPass;
            $mail->Port = (int) $this->emailConfig->SMTPPort;
            $mail->SMTPSecure = (string) $this->emailConfig->SMTPCrypto;
            $mail->CharSet = (string) $this->emailConfig->charset;
            $mail->isHTML(true);

            $from = $this->emailConfig->fromEmail ?: $this->emailConfig->SMTPUser;
            $fromName = $this->emailConfig->fromName ?: 'Booking Service';

            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = trim(strip_tags($body));

            return $mail->send();
        } catch (PHPMailerException $exception) {
            log_message('error', 'PHPMailer gagal mengirim email: {message}', [
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
