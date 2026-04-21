<?php

namespace ValueObject;
enum DataType: string
{
    case TEXT = "text";
    case INTEGER = "integer";
    case FLOAT = "float";
    case BOOLEAN = "boolean";
    case FILE = "file";
    case JSON = "json";
    case XML = "xml";
    case IPV4 = "ipv4";
    case PORT = "port";
}