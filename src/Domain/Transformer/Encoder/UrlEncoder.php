<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Encodes using urlencode(): spaces become +, special chars become %XX.
 * Suitable for application/x-www-form-urlencoded bodies.
 */
class UrlEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return urlencode($this->ensureString($value));
    }
}
