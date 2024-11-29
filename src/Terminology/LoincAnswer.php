<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\LoincAnswer.
 *
 * @property string $LoincNumber
 * @property string $AnswerListId
 * @property string $AnswerListName
 * @property string $AnswerStringId
 * @property integer $SequenceNumber
 * @property string $DisplayText
 * @property string $ExtCodeId
 * @property string $ExtCodeDisplayName
 * @property string $ExtCodeSystem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class LoincAnswer extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.loinc_answer_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [];
}
