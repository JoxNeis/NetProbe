<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Converts the input string to UTF-8.
 * Assumes the source encoding is the current internal mbstring encoding
 * (usually UTF-8). Useful as a normalisation step.
 */
class Utf8Encoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return mb_convert_encoding($this->ensureString($value), 'UTF-8');
    }
}
