<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

class KptlBaseModifierMapping extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.kptl_base_modifier_mapping_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'display' => 'string',
        'modifier_1' => 'string',
        'modifier_2' => 'string',
        'modifier_3' => 'string',
        'modifier_4' => 'string',
        'modifier_5' => 'string',
        'base_code' => 'string',
        'modifier_code_1' => 'string',
        'modifier_code_2' => 'string',
        'modifier_code_3' => 'string',
        'modifier_code_4' => 'string',
        'modifier_code_5' => 'string',
    ];
}
