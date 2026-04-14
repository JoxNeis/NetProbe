<?php

require_once(__DIR__ . "/Encoder.php");

use Encoder\Encoder;

class Base64Encoder implements Encoder
{
    public function encode(mixed $value): string
    {
        return base64_encode($value);
    }
}