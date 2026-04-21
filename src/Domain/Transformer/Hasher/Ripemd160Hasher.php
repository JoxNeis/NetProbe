<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Ripemd160Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('ripemd160', $this->ensureString($value));
    }
}
