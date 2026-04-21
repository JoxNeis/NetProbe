<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Applies ROT-13 substitution cipher (rotates a–z and A–Z by 13 positions).
 * Note: ROT-13 is its own inverse — encoding and decoding use the same operation.
 */
class Rot13Encoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return str_rot13($this->ensureString($value));
    }
}
