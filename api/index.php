<?php

foreach (['/tmp/storage/framework/views', '/tmp/storage/framework/cache', '/tmp/storage/framework/sessions', '/tmp/storage/logs', '/tmp/views'] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Load .env.vercel directly (filesystem is read-only on Vercel)
$vercelEnv = __DIR__ . '/../.env.vercel';
if (!file_exists(__DIR__ . '/../.env') && file_exists($vercelEnv)) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname($vercelEnv), '.env.vercel');
    $dotenv->load();
}

// Ensure services.php exists (Vercel Git deploy may not generate it)
$servicesCache = __DIR__ . '/../bootstrap/cache/services.php';
if (!file_exists($servicesCache)) {
    // Write to /tmp since filesystem is read-only
    putenv('APP_SERVICES_CACHE=/tmp/services.php');
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
}

$packagesCache = __DIR__ . '/../bootstrap/cache/packages.php';
if (!file_exists($packagesCache)) {
    putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
}

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath('/tmp/storage');

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Illuminate\Http\Request::capture());
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo $e->getTraceAsString() . "\n\n";

    $prev = $e->getPrevious();
    while ($prev) {
        echo "CAUSED BY: " . $prev->getMessage() . "\n";
        echo "FILE: " . $prev->getFile() . ":" . $prev->getLine() . "\n\n";
        $prev = $prev->getPrevious();
    }
}
