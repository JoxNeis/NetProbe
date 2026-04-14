<?php

namespace Encoder;

require_once(__DIR__ . "/Base64Encoder.php");
require_once(__DIR__ . "/HexEncoder.php");
require_once(__DIR__ . "/JsonEncoder.php");
require_once(__DIR__ . "/UrlEncoder.php");
require_once(__DIR__ . "/../../VALUES/PARAMETER/EncodeType.php");

use Exception;
use Values\Parameter\EncodeType;

class EncoderFactory
{
    #region FACTORY
    public static function create(EncodeType $type): Encoder
    {
        return match ($type) {

            EncodeType::BASE64 => new Base64Encoder(),
            EncodeType::HEX => new HexEncoder(),
            EncodeType::JSON => new JsonEncoder(),
            EncodeType::URL => new UrlEncoder(),
            default =>
            throw new Exception(
                "Unsupported encoder type: "
                . $type->value
            )
        };
    }

    #endregion
}