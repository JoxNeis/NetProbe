<?php

namespace Transformer\Encoder;

require_once(__DIR__ . "/HexEncoder.php");
require_once(__DIR__ . "/Base64Encoder.php");
require_once(__DIR__ . "/../ValueObject/EncodeType.php");

use Exception;
use Transformer\Encoder\HexEncoder;
use Transformer\Encoder\Base64Encoder;
use ValueObject\Transformer\EncodeType;

class EncoderFactory
{
    #region FACTORY
    public static function create(EncodeType $type): Encoder
    {
        return match ($type) {
            EncodeType::BASE64 => new Base64Encoder(),
            EncodeType::HEX => new HexEncoder(),
            default =>
            throw new Exception(
                "Unsupported encoder type: "
                . $type->value
            )
        };
    }
    #endregion
}