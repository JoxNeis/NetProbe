<?php

namespace Encoder;

require_once(__DIR__ . "/AbstractEncoder.php");

use Exception;

class JsonEncoder extends AbstractEncoder
{
    protected function modifier(mixed $value): string
    {
        if (is_string($value)) {
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }

            $encoded = json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );

        } else {
            $encoded = json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($encoded === false) {
            throw new Exception(
                "JSON encoding failed: "
                . json_last_error_msg()
            );
        }

        return $encoded;
    }
}