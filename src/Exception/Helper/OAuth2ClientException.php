<?php

namespace Satusehat\Integration\Exception\Helper;

use Exception;

class OAuth2ClientException extends Exception
{
    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
