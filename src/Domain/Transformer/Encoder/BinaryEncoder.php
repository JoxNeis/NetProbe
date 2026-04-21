<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Returns the raw binary representation of the value.
 * If the value is an integer it is packed as a 32-bit big-endian unsigned long.
 * All other values are cast to string and returned as-is (already binary-safe).
 */
class BinaryEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        if (is_int($value)) {
            return pack('N', $value);
        }
        return $this->ensureString($value);
    }
}
