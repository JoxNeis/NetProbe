<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

class NoneEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        return $this->ensureString($value);
    }
}
