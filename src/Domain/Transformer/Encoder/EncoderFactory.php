<?php
 
namespace Transformer\Encoder;
 
require_once(__DIR__ . "/NoneEncoder.php");
require_once(__DIR__ . "/Base64Encoder.php");
require_once(__DIR__ . "/Base64UrlEncoder.php");
require_once(__DIR__ . "/HexEncoder.php");
require_once(__DIR__ . "/BinaryEncoder.php");
require_once(__DIR__ . "/UrlEncoder.php");
require_once(__DIR__ . "/RawUrlEncoder.php");
require_once(__DIR__ . "/HtmlSpecialEncoder.php");
require_once(__DIR__ . "/QuotedPrintableEncoder.php");
require_once(__DIR__ . "/UuencodeEncoder.php");
require_once(__DIR__ . "/Rot13Encoder.php");
require_once(__DIR__ . "/Utf8Encoder.php");
require_once(__DIR__ . "/Utf16Encoder.php");
require_once(__DIR__ . "/Utf32Encoder.php");
require_once(__DIR__ . "/Iso88591Encoder.php");
 
use ValueObject\Transformer\EncodeType;
 
class EncoderFactory
{
    #region FACTORY
    public static function create(EncodeType $type): Encoder
    {
        return match ($type) {
            EncodeType::NONE              => new NoneEncoder(),
            EncodeType::BASE64            => new Base64Encoder(),
            EncodeType::BASE64_URL        => new Base64UrlEncoder(),
            EncodeType::HEX              => new HexEncoder(),
            EncodeType::BINARY            => new BinaryEncoder(),
            EncodeType::URL              => new UrlEncoder(),
            EncodeType::RAW_URL          => new RawUrlEncoder(),
            EncodeType::HTML_SPECIAL      => new HtmlSpecialEncoder(),
            EncodeType::QUOTED_PRINTABLE  => new QuotedPrintableEncoder(),
            EncodeType::UUENCODE          => new UuencodeEncoder(),
            EncodeType::ROT13             => new Rot13Encoder(),
            EncodeType::UTF8              => new Utf8Encoder(),
            EncodeType::UTF16             => new Utf16Encoder(),
            EncodeType::UTF32             => new Utf32Encoder(),
            EncodeType::ISO_8859_1        => new Iso88591Encoder(),
        };
    }
    #endregion
}
 
