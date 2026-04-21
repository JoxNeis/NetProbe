<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

/**
 * Converts HTML special characters to their entities using htmlspecialchars().
 * Encodes: & " ' < >
 * Uses ENT_QUOTES | ENT_SUBSTITUTE so both single and double quotes are escaped
 * and invalid code unit sequences are substituted rather than discarded.
 */
class HtmlSpecialEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return htmlspecialchars(
            $this->ensureString($value),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}
