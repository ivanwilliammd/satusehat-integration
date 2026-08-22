<?php
/**
 * Standalone queue enqueue CLI — no Laravel required.
 *
 * Usage:
 *   php queue/enqueue.php bundle --payload='{"resourceType":"Bundle",...}'
 *   php queue/enqueue.php single POST Patient --payload='{"resourceType":"Patient",...}'
 *   php queue/enqueue.php status
 *   php queue/enqueue.php dlq
 *
 * Options:
 *   --config=config.php    Path to config.php (default: queue/config.php)
 *   --type=transaction    Bundle type: transaction | batch
 *   --user=system         User ID
 */

$base = dirname(__DIR__);
require_once $base . '/vendor/autoload.php';

use Satusehat\Integration\Queue\SqliteQueue;

$opts = getopt('', ['config::', 'type::', 'user::', 'help']);
if (isset($opts['help'])) {
    echo <<<HELP
SATUSEHAT Queue Enqueue CLI (standalone)

Usage:
  php queue/enqueue.php bundle --payload='JSON' [--type=transaction] [--user=system]
  php queue/enqueue.php single POST ResourceType URL [--payload='JSON']
  php queue/enqueue.php status
  php queue/enqueue.php dlq
  php queue/enqueue.php reset

Options:
  --config   Path to config.php (default: queue/config.php)
  --type     Bundle type: transaction | batch (default: transaction)
  --user     User/system identifier (default: system)

Examples:
  # Enqueue a Bundle
  php queue/enqueue.php bundle --payload='{"resourceType":"Bundle",...}'

  # Enqueue a single Patient create
  php queue/enqueue.php single POST Patient --payload='{"resourceType":"Patient",...}'

  # Enqueue a Patient update (PUT)
  php queue/enqueue.php single PUT Patient/123 --payload='{"resourceType":"Patient","id":"123",...}'

  # Check queue status
  php queue/enqueue.php status

  # Show DLQ jobs
  php queue/enqueue.php dlq

  # Reset failed/dlq back to pending
  php queue/enqueue.php reset

HELP;
    exit(0);
}

$configPath = $opts['config'] ?? __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    fwrite(STDERR, "ERROR: Config not found: {$configPath}\n");
    exit(1);
}
$config = require $configPath;

$pdo = new PDO('sqlite:' . $config['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$queue = new SqliteQueue($pdo);

$args = array_slice($argv ?? [], 1);
$args = array_filter($args, fn($a) => !str_starts_with($a, '--'));
$cmd = $args[0] ?? 'status';
$bundleType = $opts['type'] ?? 'transaction';
$userId = $opts['user'] ?? 'system';

switch ($cmd) {
    case 'bundle':
        $payloadArg = $opts['payload'] ?? $args[1] ?? '';
        if (!$payloadArg) {
            fwrite(STDERR, "ERROR: --payload required\n");
            exit(1);
        }
        $payload = json_decode($payloadArg, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            fwrite(STDERR, "ERROR: Invalid JSON payload: " . json_last_error_msg() . "\n");
            exit(1);
        }
        $job = $queue->enqueueBundle($payload, $bundleType, $userId);
        echo "Enqueued Bundle job #{$job['id']} (uuid={$job['uuid']})\n";
        break;

    case 'single':
        $method = strtoupper($args[1] ?? 'POST');
        $resourceType = $args[2] ?? '';
        $url = $args[3] ?? '';
        if (!$resourceType || !$url) {
            fwrite(STDERR, "Usage: enqueue.php single METHOD ResourceType URL [--payload='JSON']\n");
            exit(1);
        }
        $payloadArg = $opts['payload'] ?? null;
        $payload = $payloadArg ? json_decode($payloadArg, true) : null;
        $job = $queue->enqueue($method, $resourceType, $url, $payload, null, null, $userId);
        echo "Enqueued {$method} {$url} job #{$job['id']} (uuid={$job['uuid']})\n";
        break;

    case 'status':
        $stats = $queue->stats();
        echo "Queue status:\n";
        printf("  pending:    %d\n", $stats['pending']);
        printf("  processing: %d\n", $stats['processing']);
        printf("  success:    %d\n", $stats['success']);
        printf("  failed:     %d\n", $stats['failed']);
        printf("  dlq:        %d\n", $stats['dlq']);
        break;

    case 'dlq':
        $jobs = $queue->dlqJobs(20);
        if (!$jobs) {
            echo "DLQ empty.\n";
            break;
        }
        echo "DLQ jobs:\n";
        foreach ($jobs as $j) {
            printf("  #%-4d uuid=%s attempts=%d reason=%s\n",
                $j['id'],
                substr($j['uuid'], 0, 8) . '...',
                $j['attempts'],
                substr($j['dlq_reason'] ?? '', 0, 60)
            );
        }
        break;

    case 'reset':
        $count = $queue->resetAll();
        echo "Reset {$count} jobs to pending.\n";
        break;

    default:
        fwrite(STDERR, "Unknown command: {$cmd}\n");
        exit(1);
}
