<?php

namespace Encoder;

interface Encoder
{
    public function encode(mixed $value): string;
}