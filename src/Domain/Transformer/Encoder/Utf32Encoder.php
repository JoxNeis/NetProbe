<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Converts the input string to UTF-32 (with BOM).
 * The resulting string is binary; handle accordingly.
 */
class Utf32Encoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return mb_convert_encoding($this->ensureString($value), 'UTF-32');
    }
}
