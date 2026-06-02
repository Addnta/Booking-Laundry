<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $m = new mysqli('localhost', 'root', '', 'service_booking_db', 3306);
    echo "CONNECTED\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
