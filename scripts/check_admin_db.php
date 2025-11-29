<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "database file missing: $dbPath\n";
    exit(1);
}
try {
    $db = new PDO('sqlite:' . $dbPath);
    if (in_array('--columns', $argv, true)) {
        $cols = $db->query("PRAGMA table_info('users')")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $c) {
            echo $c['name'] . "\n";
        }
        exit(0);
    }

    $stmt = $db->prepare('SELECT email,password,role FROM users WHERE email = :email');
    $stmt->execute([':email' => '300627-101']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (! $row) {
        echo "missing\n";
        exit(0);
    }
    echo "found\n";
    echo "email={$row['email']}\n";
    echo "passHash={$row['password']}\n";
    echo "role=" . ($row['role'] ?? 'NULL') . "\n";
    // verify password using password_verify
    $ok = password_verify('TuroTugma@2025/DNHS', $row['password']) ? 'ok' : 'fail';
    echo "check={$ok}\n";
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage() . "\n";
    exit(1);
}
