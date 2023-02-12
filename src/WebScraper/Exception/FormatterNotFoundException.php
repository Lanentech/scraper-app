<?php

namespace App\WebScraper\Exception;

use Throwable;

class FormatterNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}