<?php

namespace Satusehat\Integration\Exception\Terminology;

use Exception;

class TerminologyException extends Exception
{
    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
