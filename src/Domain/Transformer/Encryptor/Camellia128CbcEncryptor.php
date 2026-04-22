<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/AbstractEncryptor.php");

class Camellia128CbcEncryptor extends AbstractEncryptor
{
    private const CIPHER = 'camellia-128-cbc';

    public function encrypt(string $value, string $key): string
    {
        return $this->opensslEncrypt($value, $key, self::CIPHER);
    }
}
