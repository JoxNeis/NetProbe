<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/Aes128CbcEncryptor.php");
require_once(__DIR__ . "/Aes192CbcEncryptor.php");
require_once(__DIR__ . "/Aes256CbcEncryptor.php");
require_once(__DIR__ . "/Aes128GcmEncryptor.php");
require_once(__DIR__ . "/Aes192GcmEncryptor.php");
require_once(__DIR__ . "/Aes256GcmEncryptor.php");
require_once(__DIR__ . "/ChaCha20Poly1305Encryptor.php");
require_once(__DIR__ . "/DesCbcEncryptor.php");
require_once(__DIR__ . "/DesEde3CbcEncryptor.php");
require_once(__DIR__ . "/BfCbcEncryptor.php");
require_once(__DIR__ . "/Camellia128CbcEncryptor.php");
require_once(__DIR__ . "/Camellia192CbcEncryptor.php");
require_once(__DIR__ . "/Camellia256CbcEncryptor.php");

use ValueObject\Transformer\EncryptType;

class EncryptorFactory
{
    #region FACTORY
    public static function create(EncryptType $type): AbstractEncryptor
    {
        return match ($type) {
            EncryptType::AES_128_CBC       => new Aes128CbcEncryptor(),
            EncryptType::AES_192_CBC       => new Aes192CbcEncryptor(),
            EncryptType::AES_256_CBC       => new Aes256CbcEncryptor(),
            EncryptType::AES_128_GCM       => new Aes128GcmEncryptor(),
            EncryptType::AES_192_GCM       => new Aes192GcmEncryptor(),
            EncryptType::AES_256_GCM       => new Aes256GcmEncryptor(),
            EncryptType::CHACHA20_POLY1305 => new ChaCha20Poly1305Encryptor(),
            EncryptType::DES_CBC           => new DesCbcEncryptor(),
            EncryptType::DES_EDE3_CBC      => new DesEde3CbcEncryptor(),
            EncryptType::BF_CBC            => new BfCbcEncryptor(),
            EncryptType::CAMELLIA_128_CBC  => new Camellia128CbcEncryptor(),
            EncryptType::CAMELLIA_192_CBC  => new Camellia192CbcEncryptor(),
            EncryptType::CAMELLIA_256_CBC  => new Camellia256CbcEncryptor(),
        };
    }
    #endregion
}
