<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "database file missing: $dbPath\n";
    exit(1);
}
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->exec("UPDATE users SET role='it_coordinator' WHERE email='300627-101'");
    $db->exec("UPDATE users SET role='scheduler' WHERE email='300627-201'");
    echo "roles updated\n";
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage() . "\n";
    exit(1);
}
