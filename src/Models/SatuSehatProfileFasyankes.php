<?php

namespace Satusehat\Integration\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SatuSehatProfileFasyankes extends Model
{
    use HasUuids;

    public $guarded  = [];

    public function __construct(array $attributes = [])
    {
        if (!isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection'));
        }

        if (!isset($this->table)) {
            $this->setTable(config('satusehatintegration.profile_fasyankes_name'));
        }

        parent::__construct($attributes);
    }
}
