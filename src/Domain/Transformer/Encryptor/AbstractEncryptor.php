<?php

namespace Transformer\Encryptor;

require_once(__DIR__ . "/Encryptor.php");

use Exception;

abstract class AbstractEncryptor implements Encryptor
{
    /**
     * Generate a cryptographically secure IV for the given cipher.
     */
    protected function generateIv(string $cipher): string
    {
        $length = openssl_cipher_iv_length($cipher);
        if ($length === false || $length === 0) {
            return '';
        }
        return openssl_random_pseudo_bytes($length);
    }

    /**
     * Encrypt, prepend the IV, then base64-encode the result
     * so the output is always a safe, portable string.
     *
     * Format: base64( IV . ciphertext )
     */
    protected function opensslEncrypt(string $value, string $key, string $cipher): string
    {
        $iv = $this->generateIv($cipher);
        $encrypted = openssl_encrypt($value, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new Exception("Encryption failed for cipher: {$cipher}");
        }

        return base64_encode($iv . $encrypted);
    }

    abstract public function encrypt(string $value, string $key): string;
}
