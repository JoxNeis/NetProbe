<?php

namespace ValueObject\Http;

enum HttpHeaderCategory: string
{
    // General
    case ACCEPT = "Accept";
    case ACCEPT_ENCODING = "Accept-Encoding";
    case ACCEPT_LANGUAGE = "Accept-Language";
    case AUTHORIZATION = "Authorization";
    case CACHE_CONTROL = "Cache-Control";
    case CONNECTION = "Connection";
    case CONTENT_LENGTH = "Content-Length";
    case CONTENT_TYPE = "Content-Type";
    case COOKIE = "Cookie";
    case HOST = "Host";
    case ORIGIN = "Origin";
    case PRAGMA = "Pragma";
    case REFERER = "Referer";
    case USER_AGENT = "User-Agent";

    // Request
    case IF_MATCH = "If-Match";
    case IF_MODIFIED_SINCE = "If-Modified-Since";
    case IF_NONE_MATCH = "If-None-Match";
    case RANGE = "Range";

    // Response
    case LOCATION = "Location";
    case SERVER = "Server";
    case SET_COOKIE = "Set-Cookie";
    case WWW_AUTHENTICATE = "WWW-Authenticate";


    // CORS
    case ACCESS_CONTROL_ALLOW_ORIGIN =
    "Access-Control-Allow-Origin";

    case ACCESS_CONTROL_ALLOW_HEADERS =
    "Access-Control-Allow-Headers";

    case ACCESS_CONTROL_ALLOW_METHODS =
    "Access-Control-Allow-Methods";

    case ACCESS_CONTROL_EXPOSE_HEADERS =
    "Access-Control-Expose-Headers";

    // Custom prefix helper
    case X_REQUESTED_WITH = "X-Requested-With";
    case X_API_KEY = "X-API-Key";
}