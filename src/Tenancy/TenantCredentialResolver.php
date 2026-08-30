<?php

namespace Satusehat\Integration\Tenancy;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class TenantCredentialResolver
{
    public function resolve(?string $teamKey = null): array
    {
        $config = Config::get('satusehatintegration');
        $override = $config['ss_parameter_override'] ?? [];
        $overrideEnabled = is_array($override) ? ($override['enabled'] ?? false) : (bool) $override;
        $driver = is_array($override) ? ($override['driver'] ?? 'env') : 'env';

        if (! $overrideEnabled) {
            return $this->fromEnv();
        }

        if ($driver !== 'database') {
            return $this->fromArray($override['parameters'] ?? []);
        }

        $tenancy = $config['tenancy'] ?? [];
        if (! ($tenancy['enabled'] ?? false)) {
            return $this->fromArray($override['parameters'] ?? []);
        }

        $teamKey = $teamKey ?? $tenancy['default_team_key'] ?? null;
        if (! $teamKey) {
            throw new InvalidArgumentException('Team key is required when SATUSEHAT multi-tenancy is enabled.');
        }

        $ttl = $tenancy['cache_ttl'] ?? 300;
        $cacheKey = "satusehat.tenants.{$teamKey}";

        return Cache::remember($cacheKey, $ttl, function () use ($teamKey, $tenancy) {
            $record = DB::connection($tenancy['connection'] ?? Config::get('satusehatintegration.database_connection_master', 'mysql'))
                ->table($tenancy['teams_table_name'])
                ->where($tenancy['team_key_column'], $teamKey)
                ->first();

            if (! $record) {
                throw new RuntimeException("SATUSEHAT tenant [{$teamKey}] was not found.");
            }

            $columns = $tenancy['columns'];

            return [
                'client_id' => $record->{$columns['client_id']},
                'client_secret' => $record->{$columns['client_secret']},
                'organization_id' => $record->{$columns['organization_id']},
                'organization_name' => $record->{$columns['organization_name'] ?? $columns['organization_id']},
                'environment' => $record->{$columns['environment']},
            ];
        });
    }

    protected function fromEnv(): array
    {
        return $this->fromArray([
            'client_id' => env('SATUSEHAT_CLIENT_ID'),
            'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),
            'organization_id' => env('SATUSEHAT_ORGANIZATION_ID'),
            'organization_name' => env('SATUSEHAT_ORGANIZATION_NAME'),
            'environment' => env('SATUSEHAT_ENVIRONMENT'),
        ]);
    }

    protected function fromArray(array $parameters): array
    {
        return [
            'client_id' => $parameters['client_id'] ?? env('SATUSEHAT_CLIENT_ID'),
            'client_secret' => $parameters['client_secret'] ?? env('SATUSEHAT_CLIENT_SECRET'),
            'organization_id' => $parameters['organization_id'] ?? env('SATUSEHAT_ORGANIZATION_ID'),
            'organization_name' => $parameters['organization_name'] ?? env('SATUSEHAT_ORGANIZATION_NAME'),
            'environment' => $parameters['environment'] ?? env('SATUSEHAT_ENVIRONMENT'),
        ];
    }
}
