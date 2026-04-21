<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Tiger160_3Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('tiger160,3', $this->ensureString($value));
    }
}
