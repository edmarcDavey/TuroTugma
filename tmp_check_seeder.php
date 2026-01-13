<?php
require __DIR__ . '/vendor/autoload.php';
try {
    $r = new ReflectionClass('Database\\Seeders\\FilipinoTeachersSeeder');
    echo $r->getFileName() . PHP_EOL;
} catch (ReflectionException $e) {
    echo 'ReflectionException: ' . $e->getMessage() . PHP_EOL;
}
