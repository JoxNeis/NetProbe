<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Ripemd128Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('ripemd128', $this->ensureString($value));
    }
}
