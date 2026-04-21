<?php

namespace Encoder;

require_once(__DIR__ . "/Encoder.php");

use Exception;

abstract class AbstractEncoder implements Encoder
{
    public function encode(mixed $value): string
    {
        if ($this->isFile($value)) {
            $value = $this->loadFile($value);
        }
        return $this->modifier($value);
    }

    protected function isFile(mixed $value): bool
    {
        return is_string($value) && file_exists($value);
    }

    protected function loadFile(string $path): string
    {
        $file = file_get_contents($path);
        if ($file === false) {
            throw new Exception("Failed to read file content: {$path}");
        }
        return $file;
    }

    protected function ensureString(mixed $value): string
    {
        return (string) $value;
    }
    abstract protected function modifier(mixed $value): string;
}