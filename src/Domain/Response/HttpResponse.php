<?php

namespace Response;

use Exception;

class HttpResponse
{
    #region FIELDS
    private int $httpStatusCode;
    private array $header;
    private string $body;
    private array $info;
    #endregion

    #region CONSTRUCTOR
    public function __construct(int $httpStatusCode, array $header, string $body, array $info)
    {
        $this->setHttpStatusCode($httpStatusCode);
        $this->setHeader($header);
        $this->setBody($body);
        $this->setInfo($info);
    }
    #endregion
    #region GETTER
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getInfo(): array
    {
        return $this->info;
    }
    #endregion
    #region SETTER
    public function setHttpStatusCode(int $httpStatusCode)
    {
        if ($httpStatusCode <= 0) {
            throw new Exception("Http Response\'s Status Code can\'t be lower than zero");
        }
        $this->httpStatusCode = $httpStatusCode;
    }
    public function setHeader(array $header)
    {
        $this->header = $header;
    }
    public function setBody(string $body)
    {
        $this->body = $body;
    }
    public function setInfo(array $info)
    {
        if (empty($info)) {
            throw new Exception("Http Response\'s Info can\'t be empty");
        }
        $this->info = $info;
    }
    #endregion
}