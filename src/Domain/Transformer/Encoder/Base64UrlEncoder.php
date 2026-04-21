<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

class Base64UrlEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        $base64 = base64_encode($this->ensureString($value));
        // RFC 4648 §5: replace + with -, / with _, strip padding =
        return rtrim(strtr($base64, '+/', '-_'), '=');
    }
}
