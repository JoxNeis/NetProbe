<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/AbstractEncryptor.php");

class Aes192CbcEncryptor extends AbstractEncryptor
{
    private const CIPHER = 'aes-192-cbc';

    public function encrypt(string $value, string $key): string
    {
        return $this->opensslEncrypt($value, $key, self::CIPHER);
    }
}
