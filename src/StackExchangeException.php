<?php
declare(strict_types=1);

namespace vielhuber\stackexchangemcp;

final class StackExchangeException extends \RuntimeException
{
    /**
     * Report failures without disclosing credentials or remote response bodies.
     */
    public static function failed(string $message, int $code = 0): self
    {
        return new self($message, $code);
    }
}
