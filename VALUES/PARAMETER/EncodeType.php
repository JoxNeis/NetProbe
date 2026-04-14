<?php

namespace Values\Parameter;
enum EncodeType: string
{
    case NONE = "none";

    case JSON = "json";
    case BASE64 = "base64";
    case HEX = "hex";
    case URL = "url";
    case MULTIPART = "multipart";
}