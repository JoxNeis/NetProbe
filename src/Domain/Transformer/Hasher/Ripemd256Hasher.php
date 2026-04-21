<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Ripemd256Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('ripemd256', $this->ensureString($value));
    }
}
