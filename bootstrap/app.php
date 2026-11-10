<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Throwable $e) {
            return response(
                "<div style='font-family:sans-serif;padding:30px;background:#fff5f5;color:#c53030;border:2px solid #feb2b2;margin:20px;border-radius:8px;'>" .
                "<h1 style='margin-top:0;'>Terdeteksi Error di Vercel:</h1>" .
                "<p style='font-size:18px;'><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>" .
                "<p><strong>Lokasi File:</strong> " . htmlspecialchars($e->getFile()) . " (Baris " . $e->getLine() . ")</p>" .
                "<h3>Detail Trace:</h3>" .
                "<pre style='background:#1a202c;color:#e2e8f0;padding:15px;border-radius:6px;overflow:auto;font-size:13px;'>" .
                htmlspecialchars($e->getTraceAsString()) .
                "</pre>" .
                "</div>",
                500
            );
        });
    })->create();

if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || !empty($_ENV['APP_STORAGE']) || getenv('APP_STORAGE')) {
    $storagePath = $_ENV['APP_STORAGE'] ?? getenv('APP_STORAGE') ?? '/tmp/storage';
    $app->useStoragePath($storagePath);
}

return $app;
