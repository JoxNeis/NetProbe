<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Crc32Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('crc32', $this->ensureString($value));
    }
}
