<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class Sha1Hasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return hash('sha1', $this->ensureString($value));
    }
}
