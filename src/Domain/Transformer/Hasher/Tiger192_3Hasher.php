<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Tiger192_3Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('tiger192,3', $this->ensureString($value));
    }
}
