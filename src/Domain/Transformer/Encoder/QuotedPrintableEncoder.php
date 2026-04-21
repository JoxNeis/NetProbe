<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Encodes using Quoted-Printable (RFC 2045).
 * Commonly used for MIME email bodies.
 */
class QuotedPrintableEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return quoted_printable_encode($this->ensureString($value));
    }
}
