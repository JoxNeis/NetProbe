<?php

namespace Transformer\Hasher;

interface Hasher
{
    public function encrypt(string $value): string;
}