<?php

namespace Satusehat\Integration\Terminology;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\FhirR4vs.
 *
 * @property string $id
 * @property string $url
 * @property string $version
 * @property string $name
 * @property string $title
 * @property string $status
 * @property bool $experimental
 * @property string $description
 * @property string $date
 * @property string $publisher
 * @property string $content
 * @property string $concept_code_l1
 * @property string $concept_display_l1
 * @property string $concept_definition_l1
 * @property string $concept_code_l2
 * @property string $concept_display_l2
 * @property string $concept_definition_l2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class FhirR4vs extends Model
{
    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_master'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.fhirr4vs_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'fhir_id' => 'string',
        'url' => 'string',
        'version' => 'string',
        'name' => 'string',
        'title' => 'string',
        'status' => 'string',
        'experimental' => 'boolean',
        'description' => 'string',
        'date' => 'string',
        'publisher' => 'string',
        'content' => 'string',
        'concept_code_l1' => 'string',
        'concept_display_l1' => 'string',
        'concept_definition_l1' => 'string',
        'concept_code_l2' => 'string',
        'concept_display_l2' => 'string',
        'concept_definition_l2' => 'string',
    ];
}
