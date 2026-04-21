<?php

namespace Transformer\Hasher;

require_once(__DIR__ . "/AbstractHasher.php");

class NoneHasher extends AbstractHasher
{
    protected function modifier(mixed $value): string
    {
        return $this->ensureString($value);
    }
}
