<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Annotation extends DataType
{
    /** @var mixed Reference|string */
    public $author = null;
    public ?string $time = null;
    public ?string $text = null;

    /**
     * @param mixed $author Reference|string
     */
    public function __construct($author, ?string $text = null, ?string $time = null)
    {
        $this->author = $author;
        $this->text = $text;
        $this->time = $time;
    }
}
