<?php
/**
 * Standalone queue worker — no Laravel required.
 *
 * Usage:
 *   php queue/worker.php [--limit=50] [--config=config.php]
 *
 * Config file (PHP array):
 *   return [
 *       'database' => '/path/to/queue.db',
 *       'oauth2' => [
 *           'base_url'  => 'https://api-satusehat.kemkes.go.id',
 *           'client_id'  => '...',
 *           'client_secret' => '...',
 *           'fhir_url'  => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1',
 *       ],
 *   ];
 */

$base = dirname(__DIR__);
require_once $base . '/vendor/autoload.php';

use Satusehat\Integration\Queue\SqliteQueue;
use Satusehat\Integration\Queue\Worker;

// ── CLI args ──────────────────────────────────────────────────────
$opts = getopt('', ['limit::', 'config::', 'help']);
if (isset($opts['help'])) {
    echo <<<HELP
SATUSEHAT Queue Worker (standalone)

Usage:
  php queue/worker.php [--limit=50] [--config=config.php]

Options:
  --limit=50    Max jobs to process (default: 50)
  --config     Path to config.php (default: config.php in queue/ dir)

Examples:
  php queue/worker.php
  php queue/worker.php --limit=100
  php queue/worker.php --config=/etc/satusehat/config.php

HELP;
    exit(0);
}

$limit = (int) ($opts['limit'] ?? 50);
$configPath = $opts['config'] ?? __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    fwrite(STDERR, "ERROR: Config file not found: {$configPath}\n");
    fwrite(STDERR, "Copy config.example.php to config.php and edit.\n");
    exit(1);
}

$config = require $configPath;

if (empty($config['database']) || empty($config['oauth2'])) {
    fwrite(STDERR, "ERROR: config.php must return ['database' => ..., 'oauth2' => [...]]\n");
    exit(1);
}

// ── Bootstrap ────────────────────────────────────────────────────
$pdo = new PDO('sqlite:' . $config['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$queue = new SqliteQueue($pdo);
$worker = new Worker($queue, $config['oauth2']);

// ── Run ─────────────────────────────────────────────────────────
echo "[" . date('Y-m-d H:i:s') . "] Starting worker (limit={$limit})...\n";

$stats = $worker->process($limit);

echo "[" . date('Y-m-d H:i:s') . "] Done. "
    . "processed={$stats['processed']} "
    . "succeeded={$stats['succeeded']} "
    . "failed={$stats['failed']} "
    . "dlq={$stats['dlq']}\n";

$pending = $queue->pendingCount();
$dlq = $queue->dlqCount();
echo "Queue: pending={$pending} dlq={$dlq}\n";
