<?php

namespace Encoder;

require_once(__DIR__ . "/Base64Encoder.php");
require_once(__DIR__ . "/HexEncoder.php");
require_once(__DIR__ . "/JsonEncoder.php");
require_once(__DIR__ . "/UrlEncoder.php");
require_once(__DIR__ . "/../ValueObject/EncodeType.php");

use Exception;
use Encoder\Base64Encoder;
use Encoder\HexEncoder;
use Encoder\JsonEncoder;
use Encoder\UrlEncoder;
use ValueObject\EncodeType;

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