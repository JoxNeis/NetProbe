<?php

enum ParameterDataType: string
{
    // Primitive values
    case TEXT = "text";
    case INTEGER = "integer";
    case FLOAT = "float";
    case BOOLEAN = "boolean";

    // Structured data
    case FILE = "file";
    case JSON = "json";
    case XML = "xml";

    // Special validated types
    case IPV4 = "ipv4";
    case PORT = "port";
}