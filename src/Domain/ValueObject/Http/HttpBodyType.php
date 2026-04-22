<?php

namespace ValueObject\Http;

enum HttpBodyType: string
{
    case FORM_URLENCODED    = "application/x-www-form-urlencoded";
    case FORM_MULTIPART     = "multipart/form-data";

    case JSON               = "application/json";
    case JSON_LD            = "application/ld+json";
    case XML                = "application/xml";
    case XML_TEXT           = "text/xml";
    case YAML               = "application/yaml";
    case CBOR               = "application/cbor";
    case MSGPACK            = "application/msgpack";

    case TEXT_PLAIN         = "text/plain";
    case TEXT_HTML          = "text/html";
    case TEXT_CSV           = "text/csv";
    case TEXT_MARKDOWN      = "text/markdown";

    case OCTET_STREAM       = "application/octet-stream";
    case PDF                = "application/pdf";
    case ZIP                = "application/zip";
    case GZIP               = "application/gzip";

    case IMAGE_JPEG         = "image/jpeg";
    case IMAGE_PNG          = "image/png";
    case IMAGE_GIF          = "image/gif";
    case IMAGE_WEBP         = "image/webp";
    case IMAGE_SVG          = "image/svg+xml";

    case GRAPHQL            = "application/graphql";

    case JSON_PATCH         = "application/json-patch+json";
    case MERGE_PATCH        = "application/merge-patch+json";

    #region HELPERS
    /**
     * Returns the Content-Type header string.
     * Optionally appends a charset (e.g. "application/json; charset=utf-8").
     */
    public function withCharset(string $charset = 'utf-8'): string
    {
        return $this->value . '; charset=' . $charset;
    }

    /**
     * Returns the Content-Type header string with a multipart boundary.
     * Only meaningful for FORM_MULTIPART.
     */
    public function withBoundary(string $boundary): string
    {
        return $this->value . '; boundary=' . $boundary;
    }

    /**
     * Whether this type carries a structured text body (JSON, XML, YAML…)
     * that should typically include a charset directive.
     */
    public function isText(): bool
    {
        return match ($this) {
            self::JSON,
            self::JSON_LD,
            self::JSON_PATCH,
            self::MERGE_PATCH,
            self::XML,
            self::XML_TEXT,
            self::YAML,
            self::GRAPHQL,
            self::TEXT_PLAIN,
            self::TEXT_HTML,
            self::TEXT_CSV,
            self::TEXT_MARKDOWN,
            self::FORM_URLENCODED => true,
            default               => false,
        };
    }

    /**
     * Whether this type carries binary content.
     */
    public function isBinary(): bool
    {
        return match ($this) {
            self::OCTET_STREAM,
            self::PDF,
            self::ZIP,
            self::GZIP,
            self::CBOR,
            self::MSGPACK,
            self::IMAGE_JPEG,
            self::IMAGE_PNG,
            self::IMAGE_GIF,
            self::IMAGE_WEBP,
            self::IMAGE_SVG  => true,
            default          => false,
        };
    }
    #endregion
}