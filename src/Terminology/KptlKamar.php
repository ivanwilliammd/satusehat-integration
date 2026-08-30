<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

class KptlKamar extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.kptl_kamar_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'nama_tindakan_dan_layanan' => 'string',
        'base_code' => 'string',
        'allowed_modifiers' => 'string',
        'kode_kptl' => 'string',
        'display' => 'string',
        'code_system' => 'string',
        'version' => 'string',
    ];
}
