<?php

namespace Transformer\Hasher;

use Transformer\Transformer;

abstract class AbstractHasher extends Transformer
{
    public function hash(mixed $value): string
    {
        if ($this->isFile($value)) {
            $value = $this->loadFile($value);
        }
        return $this->modifier($value);
    }
}