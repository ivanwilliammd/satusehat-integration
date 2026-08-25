<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

class PayloadBuilderSpecimen extends Builder
{
    protected string $resourceType = 'Specimen';

    public function __construct() { $this->data['resourceType'] = $this->resourceType; }

    public function setId(string $id): self { $this->set('id', $id); return $this; }
    public function setStatus(string $status): self { $this->set('status', $status); return $this; }
    public function setType(CodeableConcept $type): self { $this->set('type', $type->toArray()); return $this; }
    public function setSubject(Reference $subject): self { $this->set('subject', $subject->toArray()); return $this; }
    public function setReceivedTime(string $dateTime): self { $this->set('receivedTime', $dateTime); return $this; }
    public function setCollectedDateTime(string $dateTime): self { $this->set('collection/collectedDateTime', $dateTime); return $this; }
    public function setCollector(Reference $collector): self { $this->set('collection/collector', $collector->toArray()); return $this; }
    public function setFastingStatusCodeableConcept(CodeableConcept $status): self { $this->set('collection/fastingStatusCodeableConcept', $status->toArray()); return $this; }
    public function setMethod(CodeableConcept $method): self { $this->set('collection/method', $method->toArray()); return $this; }
    public function setQuantity(Quantity $quantity): self { $this->set('collection/quantity', $quantity->toArray()); return $this; }
    public function setBodySite(CodeableConcept $bodySite): self { $this->set('collection/bodySite', $bodySite->toArray()); return $this; }

    public function addIdentifier(Identifier $identifier): self { $this->push('identifier', $identifier->toArray()); return $this; }
    public function addRequest(Reference $request): self { $this->push('request', $request->toArray()); return $this; }
    public function addCondition(string $text): self { $this->push('condition', ['text' => $text]); return $this; }
    public function addProcessing(string $timeDateTime): self { $this->push('processing', ['timeDateTime' => $timeDateTime]); return $this; }
    public function addExtension(string $url, string $value): self { $this->push('extension', ['url' => $url, 'valueString' => $value]); return $this; }
    public function addTransportedTime(string $dateTime): self { $this->push('extension', ['url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime', 'valueDateTime' => $dateTime]); return $this; }
    public function addTransportedPerson(string $name, array $telecom = []): self { $this->push('extension', ['url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedPerson', 'valueContactDetail' => ['name' => $name, 'telecom' => $telecom]]); return $this; }
    public function addReceivedPerson(Reference $person): self { $this->push('extension', ['url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/ReceivedPerson', 'valueReference' => $person->toArray()]); return $this; }
}
