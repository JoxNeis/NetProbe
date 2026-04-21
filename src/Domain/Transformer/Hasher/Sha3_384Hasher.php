<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Sha3_384Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('sha3-384', $this->ensureString($value));
    }
}
