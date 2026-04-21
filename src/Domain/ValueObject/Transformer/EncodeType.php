<?php

namespace ValueObject\Transformer;

enum EncodeType: string
{
    case NONE = 'none';
    case BASE64 = 'base64';
    case BASE64_URL = 'base64_url';
    case HEX = 'hex';
    case BINARY = 'binary';
    case URL = 'url';
    case RAW_URL = 'raw_url'; 
    case HTML_SPECIAL = 'html_special'; 
    case QUOTED_PRINTABLE = 'quoted_printable';
    case UUENCODE = 'uuencode';
    case ROT13 = 'rot13';
    case UTF8 = 'utf8';
    case UTF16 = 'utf16';
    case UTF32 = 'utf32';
    case ISO_8859_1 = 'iso-8859-1';
}