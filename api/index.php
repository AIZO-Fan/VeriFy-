<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
$_SERVER['APP_STORAGE'] = $storagePath;

try {
    // Forward the request to Laravel public entrypoint
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<div style="font-family:sans-serif;padding:30px;background:#fff5f5;color:#c53030;border:1px solid #feb2b2;border-radius:8px;margin:20px;">';
    echo '<h2 style="margin-top:0;">Laravel Server Error on Vercel</h2>';
    echo '<p><strong>Pesan Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' (Baris ' . $e->getLine() . ')</p>';
    echo '<h3>Stack Trace:</h3>';
    echo '<pre style="background:#2d3748;color:#f7fafc;padding:15px;border-radius:5px;overflow:auto;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}

