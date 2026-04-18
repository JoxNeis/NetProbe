<?php

namespace Request;

use ValueObject\HttpRequestMethod;

class HttpRequest
{
    #region FIELDS
    private string $address;
    private HttpRequestMethod $method;
    private array  $headers;
    private array  $body;
    private string $queryString;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
        string $address,
        HttpRequestMethod $method,
        array  $headers,
        array  $body,
        string $queryString
    ) {
        $this->address         = $address;
        $this->method      = $method;
        $this->headers     = $headers;
        $this->body        = $body;
        $this->queryString = $queryString;
    }
    #endregion

    #region GETTER
    public function getaddress(): string
    {
        return $this->address;
    }

    public function getMethod(): HttpRequestMethod
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

    public function getFulladdress(): string
    {
        if ($this->queryString === '') {
            return $this->address;
        }
        $separator = str_contains($this->address, '?') ? '&' : '?';
        return $this->address . $separator . $this->queryString;
    }
    #endregion

    #region UTILS
    public function toArray(): array
    {
        return [
            'address'     => $this->getAddress(),
            'method'      => $this->method,
            'headers'     => $this->headers,
            'body'        => $this->body,
            'queryString' => $this->queryString,
        ];
    }
    #endregion
}