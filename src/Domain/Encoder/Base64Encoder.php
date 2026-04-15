<?php

namespace Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

class Base64Encoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return base64_encode($this->ensureString($value));
    }
}