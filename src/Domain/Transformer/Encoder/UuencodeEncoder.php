<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Encodes using Unix-to-Unix encoding (uuencode).
 * Historically used for binary-to-text in email and Usenet.
 */
class UuencodeEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return convert_uuencode($this->ensureString($value));
    }
}
