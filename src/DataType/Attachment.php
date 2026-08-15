<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property string|null $contentType
 * @property string|null $language
 * @property string|null $data
 * @property string|null $url
 * @property int|null $size
 * @property string|null $hash
 * @property string|null $title
 * @property string|null $creation
 */
class Attachment extends DataType
{
    public ?string $contentType = null;
    public ?string $language = null;
    public ?string $data = null;
    public ?string $url = null;
    public ?int $size = null;
    public ?string $hash = null;
    public ?string $title = null;
    public ?string $creation = null;

    public function __construct(
        ?string $contentType = null,
        ?string $data = null,
        ?string $url = null
    ) {
        $this->contentType = $contentType;
        $this->data = $data;
        $this->url = $url;
    }
}
