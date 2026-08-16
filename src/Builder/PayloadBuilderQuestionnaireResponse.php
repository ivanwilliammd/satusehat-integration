<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Reference;

/**
 * QuestionnaireResponse FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/questionnaireresponse.html
 */
class PayloadBuilderQuestionnaireResponse extends Builder
{
    protected string $resourceType = 'QuestionnaireResponse';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function setStatus(string $status = 'completed'): self
    {
        $status = strtolower($status);
        $validStatuses = ['in-progress', 'completed', 'amended', 'entered-in-error', 'stopped'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status: must be one of ' . implode(', ', $validStatuses));
        }
        $this->set('status', $status);
        return $this;
    }

    public function setQuestionnaire(string $questionnaire): self
    {
        $this->set('questionnaire', $questionnaire);
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setEncounter(Reference $encounter): self
    {
        $this->set('encounter', $encounter->toArray());
        return $this;
    }

    public function setAuthored(string $dateTime): self
    {
        $this->set('authored', $dateTime);
        return $this;
    }

    public function setAuthor(Reference $author): self
    {
        $this->set('author', $author->toArray());
        return $this;
    }

    public function setSource(Reference $source): self
    {
        $this->set('source', $source->toArray());
        return $this;
    }

    public function addItem(string $linkId, ?string $text, CodeableConcept $answer): self
    {
        $item = [
            'linkId' => $linkId,
        ];

        if ($text !== null) {
            $item['text'] = $text;
        }

        $item['answer'] = [$answer->toArray()];

        $this->push('item', $item);
        return $this;
    }

    public function build(): array
    {
        if (!isset($this->data['status'])) {
            $this->set('status', 'completed');
        }
        return parent::build();
    }
}
