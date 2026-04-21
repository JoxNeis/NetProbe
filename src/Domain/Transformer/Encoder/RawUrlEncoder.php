<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Encodes using rawurlencode(): spaces become %20 (RFC 3986).
 * Suitable for URL path segments and query values.
 */
class RawUrlEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return rawurlencode($this->ensureString($value));
    }
}
