<?php

namespace Task;

require_once(__DIR__ . "/Task.php");
require_once(__DIR__ . "/../PARAMETER/Parameter.php");

use Task\Task;
use Parameter\Parameter;

class HttpTask extends Task
{
    #region FIELDS
    private array $header;
    private array $query;
    private array $body;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->header = [];
        $this->query = [];
        $this->body = [];
    }
    #endregion

    #region GETTER
    public function getHeader(): array
    {
        return $this->header;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): array
    {
        return $this->body;
    }
    #endregion

    #region HEADER
    public function setHeader(array $headers): void
    {
        foreach ($headers as $key => $header) {
            $this->addHeader($header, $key);
        }
    }

    public function addHeader(Parameter $parameter, mixed $key): void
    {
        $this->header[$key] = $parameter;
    }
    #endregion

    #region QUERY
    public function setQuery(array $queries): void
    {
        foreach ($queries as $key => $query) {
            $this->addQuery($query, $key);
        }
    }

    public function addQuery(Parameter $parameter, mixed $key): void
    {
        $this->query[$key] = $parameter;
    }
    #endregion

    #region BODY
    public function setBody(array $bodies): void
    {
        foreach ($bodies as $key => $body) {
            $this->addBody($body, $key);
        }
    }

    public function addBody(Parameter $parameter, mixed $key): void
    {
        $this->body[$key] = $parameter;
    }
    #endregion

    #region UTILS
    public function toArray():array{
        return [
            "header"=>$this->getHeader()
        ];
    }
    #endregion
}