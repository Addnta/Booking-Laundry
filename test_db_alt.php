<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $m = new mysqli('127.0.0.1', 'root', '', 'service_booking_db', 3306);
    echo "CONNECTED via 127.0.0.1\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
