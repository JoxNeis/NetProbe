<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class WhirlpoolHasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('whirlpool', $this->ensureString($value));
    }
}
