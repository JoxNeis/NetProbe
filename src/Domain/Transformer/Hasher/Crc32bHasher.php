<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Crc32bHasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('crc32b', $this->ensureString($value));
    }
}
