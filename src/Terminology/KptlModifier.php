<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

class KptlModifier extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.kptl_modifier_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'kategori_kelompok' => 'string',
        'item' => 'string',
        'modifier_code' => 'string',
    ];
}
