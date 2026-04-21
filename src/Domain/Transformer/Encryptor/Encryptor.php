<?php

namespace Transformer\Encryptor;

interface Encryptor
{
    public function encrypt(string $value,string $key): string;
}