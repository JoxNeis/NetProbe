<?php

namespace Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

class UrlEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return rawurlencode($this->ensureString($value));
    }
}