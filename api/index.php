<?php

foreach (['/tmp/storage/framework/views', '/tmp/storage/framework/cache', '/tmp/storage/framework/sessions', '/tmp/storage/logs', '/tmp/views'] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$envFile = __DIR__ . '/../.env';
$vercelEnv = __DIR__ . '/../.env.vercel';
if (!file_exists($envFile) && file_exists($vercelEnv)) {
    copy($vercelEnv, $envFile);
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath('/tmp/storage');

    // Manually bootstrap to catch the real error
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
