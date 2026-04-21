<?php

namespace Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

class HexEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return bin2hex($this->ensureString($value));
    }
}