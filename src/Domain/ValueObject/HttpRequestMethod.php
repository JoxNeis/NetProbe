<?php

namespace ValueObject;

enum HttpRequestMethod: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case PATCH = "PATCH";
    case DELETE = "DELETE";
    case OPTION = "OPTION";
    case HEAD = "HEAD";

}