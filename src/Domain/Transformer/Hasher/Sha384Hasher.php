<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Sha384Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('sha384', $this->ensureString($value));
    }
}
