<?php

namespace ValueObject;
enum EncodeType: string
{
    case NONE = "none";
    case BASE64 = "base64";
    case HEX = "hex";
}