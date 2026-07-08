<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= base_url('/admin/notifications/mark-all') ?>" class="btn btn-primary">
        <i class="fas fa-check-circle me-2"></i>Tandai Semua Dibaca
    </a>
    <form method="post" action="<?= base_url('/admin/reminders/h1') ?>">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-envelope me-2"></i>Kirim Reminder H-1
        </button>
    </form>
</div>

<div class="table-shell">
    <div class="p-4 p-lg-5">
        <?php if (! empty($notifications)) : ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification) : ?>
                    <div class="list-group-item border-0 rounded-4 mb-3 <?= ! empty($notification['is_read']) ? 'bg-white' : 'bg-warning-subtle' ?>">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h5 class="mb-0"><?= esc($notification['title'] ?? '-') ?></h5>
                                    <?php if (empty($notification['is_read'])) : ?>
                                        <span class="badge <?= ui_status_badge_class('notification', 'unread') ?>">Unread</span>
                                    <?php else : ?>
                                        <span class="badge <?= ui_status_badge_class('notification', 'read') ?>">Read</span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-2 text-muted"><?= esc($notification['message'] ?? '-') ?></p>
                                <small class="text-muted"><?= esc($notification['created_at'] ?? '-') ?></small>
                            </div>

                            <?php if (empty($notification['is_read'])) : ?>
                                <a href="<?= base_url('/admin/notifications/read/' . ($notification['id'] ?? 0)) ?>" class="btn btn-sm btn-outline-success">
                                    Tandai Dibaca
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0">Belum ada notifikasi.</div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
