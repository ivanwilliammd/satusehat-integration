<?php

namespace Satusehat\Integration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\SatusehatLog.
 *
 * @property string $environment
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SatusehatLog extends Model
{
    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.satusehat_log'));
        }

        parent::__construct($attributes);
    }
}
