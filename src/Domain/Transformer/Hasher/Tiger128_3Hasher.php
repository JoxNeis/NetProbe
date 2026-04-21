<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Tiger128_3Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('tiger128,3', $this->ensureString($value));
    }
}
