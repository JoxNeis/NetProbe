<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Converts the input string to ISO-8859-1 (Latin-1).
 * Characters outside the Latin-1 range are substituted.
 */
class Iso88591Encoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return mb_convert_encoding($this->ensureString($value), 'ISO-8859-1');
    }
}
