<?php

namespace Transformer\Encoder;

interface Encoder
{
    public function encode(mixed $value): string;
}