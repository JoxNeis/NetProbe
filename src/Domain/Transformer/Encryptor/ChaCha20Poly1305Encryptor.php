<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/AbstractEncryptor.php");

use Exception;

class ChaCha20Poly1305Encryptor extends AbstractEncryptor
{
    private const CIPHER  = 'chacha20-poly1305';
    private const TAG_LEN = 16;

    /**
     * ChaCha20-Poly1305 is an AEAD cipher, same as GCM — tag is required.
     *
     * Output format: base64( IV . tag . ciphertext )
     */
    public function encrypt(string $value, string $key): string
    {
        $iv  = $this->generateIv(self::CIPHER);
        $tag = '';

        $encrypted = openssl_encrypt(
            $value,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LEN
        );

        if ($encrypted === false) {
            throw new Exception("Encryption failed for cipher: " . self::CIPHER);
        }

        return base64_encode($iv . $tag . $encrypted);
    }
}
