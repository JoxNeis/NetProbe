<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Ripemd320Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('ripemd320', $this->ensureString($value));
    }
}
