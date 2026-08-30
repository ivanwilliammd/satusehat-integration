<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\Icd9cm.
 *
 * @property string $icd9cm_code
 * @property string $icd9cm_en
 * @property string $icd9cm_id
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Icd9cm extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.icd9cm_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = ['icd9cm_code' => 'string', 'icd9cm_en' => 'string', 'icd9cm_id' => 'string'];
}
