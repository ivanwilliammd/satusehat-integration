<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\Icd10.
 *
 * @property string $status
 * @property string $base_code
 * @property string $base_display
 * @property string $modifier_1
 * @property string $modifier_2
 * @property string $modifier_3
 * @property string $modifier_4
 * @property string $modifier_5
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class KptlBase extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.kptl_base_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'status' => 'string',
        'base_code' => 'string',
        'base_display' => 'string',
        'modifier_1' => 'string',
        'modifier_2' => 'string',
        'modifier_3' => 'string',
        'modifier_4' => 'string',
        'modifier_5' => 'string',
    ];
}
