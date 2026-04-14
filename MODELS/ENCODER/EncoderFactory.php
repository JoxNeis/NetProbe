<?php

namespace Encoder;

require_once(__DIR__ . "/Base64Encoder.php");
require_once(__DIR__ . "/HexEncoder.php");
require_once(__DIR__ . "/BinaryEncoder.php");
require_once(__DIR__ . "/../../VALUES/EncodeType.php");

use Exception;
use EncodeType;
use Base64Encoder;
use HexEncoder;

class EncoderFactory
{

    #region FACTORY
    public static function create(EncodeType $type)
    {
        return match($type){
            EncodeType::BASE64 => new Base64Encoder(),
            EncodeType::HEX => new HexEncoder(),
            default => throw new Exception("Unsupported encoder type")
            };
    }
    #endregion

}