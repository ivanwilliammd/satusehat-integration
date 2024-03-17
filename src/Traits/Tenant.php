<?php

namespace Satusehat\Integration\Traits;

use Satusehat\Integration\Exceptions\TenantException;
use Satusehat\Integration\Models\SatuSehatProfileFasyankes;
use Satusehat\Integration\Traits;

trait Tenant
{
    public function getProfile()
    {
        $kode = request()->get('code') ?? request()->header('X-Profile-Code');

        if (!$kode) {
            throw new TenantException("Tenant code is missing", 403);
        }

        $env = getenv('SATUSEHAT_ENV');

        if (!$env) {
            throw new TenantException("SATUSEHAT_ENV must be provided");
        }

        if (!in_array($env, ['PROD', 'STG', 'DEV'])) {
            throw new TenantException("SATUSEHAT_ENV must be 'PROD' | 'STG' | 'DEV ", 500);
        }

        $profile = SatuSehatProfileFasyankes::where('kode', $kode)
            ->where('env', $this->env)
            ->first();

        if (!$profile) {
            throw new TenantException("Tenant not found", 404);
        }

        return $profile;
    }
}
