<?php

// Vercel serverless environment is read-only except for /tmp.
// We set up temporary storage paths in /tmp for cache, views, sessions, and logs.
$storagePath = '/tmp/storage';

$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

putenv('APP_STORAGE=' . $storagePath);
$_ENV['APP_STORAGE'] = $storagePath;

// Forward the request to Laravel public entrypoint
require __DIR__ . '/../public/index.php';

