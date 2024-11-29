<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\Loinc.
 *
 * @property string $LOINC_NUM
 * @property string $COMPONENT
 * @property string $PROPERTY
 * @property string $TIME_ASPCT
 * @property string $SYSTEM
 * @property string $SCALE_TYP
 * @property string $METHOD_TYP
 * @property string $CLASS
 * @property string $CLASSTYPE
 * @property string $LOINC_COMMON_NAME
 * @property string $SHORTNAME
 * @property string $STATUS
 * @property string $VersionFirstReleased
 * @property string $VersionLastChanged
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Loinc extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.loinc_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [];
}
