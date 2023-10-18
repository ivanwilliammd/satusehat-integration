<?php

namespace Satusehat\Integration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\Icd10.
 *
 * @property string $icd10_code
 * @property string $icd10_display_en
 * @property string $icd10_display_id
 * @property boolean $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Icd10 extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.icd10_table_name'));
        }

        parent::__construct($attributes);
    }
}
