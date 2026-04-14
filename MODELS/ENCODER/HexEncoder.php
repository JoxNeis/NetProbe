<?php

require_once(__DIR__ . "/Encoder.php");

use Encoder\Encoder;

class HexEncoder implements Encoder
{
    public function encode(mixed $value): string
    {
        return bin2hex($value);
    }
}