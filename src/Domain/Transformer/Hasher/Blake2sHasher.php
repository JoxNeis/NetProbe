<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Blake2sHasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('blake2s', $this->ensureString($value));
    }
}
