<?php

namespace Request;

class HttpRequest
{
    #region FIELDS
    private string $url;
    private string $method;
    private array  $headers;
    private array  $body;
    private string $queryString;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        string $url,
        string $method,
        array  $headers,
        array  $body,
        string $queryString
    ) {
        $this->url         = $url;
        $this->method      = $method;
        $this->headers     = $headers;
        $this->body        = $body;
        $this->queryString = $queryString;
    }
    #endregion

    #region GETTER
    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    /** Resolved URL with query string appended when present. */
    public function getFullUrl(): string
    {
        if ($this->queryString === '') {
            return $this->url;
        }

        $separator = str_contains($this->url, '?') ? '&' : '?';
        return $this->url . $separator . $this->queryString;
    }
    #endregion

    #region UTILS
    public function toArray(): array
    {
        return [
            'url'         => $this->getFullUrl(),
            'method'      => $this->method,
            'headers'     => $this->headers,
            'body'        => $this->body,
            'queryString' => $this->queryString,
        ];
    }
    #endregion
}