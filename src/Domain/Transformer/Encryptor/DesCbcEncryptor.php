<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/AbstractEncryptor.php");

class DesCbcEncryptor extends AbstractEncryptor
{
    private const CIPHER = 'des-cbc';

    public function encrypt(string $value, string $key): string
    {
        return $this->opensslEncrypt($value, $key, self::CIPHER);
    }
}
