<?php

namespace Transformer;

use Exception;

abstract class Transformer{

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