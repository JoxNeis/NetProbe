<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Sha256Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('sha256', $this->ensureString($value));
    }
}
