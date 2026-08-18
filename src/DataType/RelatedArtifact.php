<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class RelatedArtifact extends DataType
{
    /**
     * documentation|justification|citation|predecessor|successor|derived-from|depends-on|composed-of
     */
    public ?string $type = null;
    public ?string $label = null;
    public ?string $display = null;
    public ?string $citation = null;
    public ?string $url = null;
    public ?Attachment $document = null;
    public ?string $resource = null;

    public function __construct(?string $type = null, ?string $display = null)
    {
        $this->type = $type;
        $this->display = $display;
    }

    public function setDocument(Attachment $document): self
    {
        $this->document = $document;
        return $this;
    }
}
