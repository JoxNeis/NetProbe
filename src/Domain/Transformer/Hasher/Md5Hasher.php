<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Md5Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('md5', $this->ensureString($value));
    }
}
