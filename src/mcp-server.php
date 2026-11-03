<?php
declare(strict_types=1);

foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $autoloadPath) {
    if (is_file($autoloadPath)) {
        require_once $autoloadPath;
        break;
    }
}

use vielhuber\simplemcp\simplemcp;

$directory = getcwd() ?: dirname(__DIR__);
$envPath = $directory . '/.env';
if (!is_file($envPath)) {
    $handle = fopen($envPath, mode: 'c');
    if ($handle === false) {
        fwrite(STDERR, 'Cannot create the StackExchange MCP environment file.' . PHP_EOL);
        exit(1);
    }
    chmod($envPath, permissions: 0o600);
    fclose($handle);
}

new simplemcp(
    name: 'stackexchange-mcp-server',
    log: $directory . '/mcp-server.log',
    discovery: __DIR__,
    auth: 'static',
    env: $envPath
);
