<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Attachment;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * DocumentReference FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/documentreference.html
 */
class PayloadBuilderDocumentReference extends Builder
{
    protected string $resourceType = 'DocumentReference';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setMasterIdentifier(Identifier $masterIdentifier): self
    {
        $this->set('masterIdentifier', $masterIdentifier->toArray());
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setDocStatus(string $docStatus): self
    {
        $this->set('docStatus', $docStatus);
        return $this;
    }

    public function addType(CodeableConcept $type): self
    {
        $this->push('type', $type->toArray());
        return $this;
    }

    public function addCategory(CodeableConcept $category): self
    {
        $this->push('category', $category->toArray());
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->set('date', $date);
        return $this;
    }

    public function addAuthor(Reference $author): self
    {
        $this->push('author', $author->toArray());
        return $this;
    }

    public function setCustodian(Reference $custodian): self
    {
        $this->set('custodian', $custodian->toArray());
        return $this;
    }

    public function addRelatesTo(string $code, Reference $target): self
    {
        $this->push('relatesTo', [
            'code' => $code,
            'target' => $target->toArray(),
        ]);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function addSecurityLabel(CodeableConcept $securityLabel): self
    {
        $this->push('securityLabel', $securityLabel->toArray());
        return $this;
    }

    public function addContent(Attachment $attachment, ?CodeableConcept $format = null): self
    {
        $content = ['attachment' => $attachment->toArray()];

        if ($format !== null) {
            $content['format'] = $format->toArray();
        }

        $this->push('content', $content);
        return $this;
    }

    public function setContext(array $context): self
    {
        $this->set('context', $context);
        return $this;
    }

    public function addExtension(string $url, mixed $value, ?string $valueType = null): self
    {
        $extension = ['url' => $url];

        if ($valueType !== null) {
            $extension['value' . ucfirst($valueType)] = $value;
        } else {
            $extension['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('extension', $extension);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}
