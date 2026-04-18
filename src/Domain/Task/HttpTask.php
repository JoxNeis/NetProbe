<?php

namespace Task;

require_once(__DIR__ . "/Task.php");
require_once(__DIR__ . "/../Parameter/Parameter.php");
require_once(__DIR__ . "/../Parameter/HttpHolder/HttpHeaderHolder.php");
require_once(__DIR__ . "/../Parameter/HttpHolder/HttpQueryHolder.php");
require_once(__DIR__ . "/../Parameter/HttpHolder/HttpBodyHolder.php");

use Task\Task;
use Parameter\Parameter;
use Parameter\HttpHolder\HttpHeaderHolder;
use Parameter\HttpHolder\HttpBodyHolder;
use Parameter\HttpHolder\HttpQueryHolder;
use ValueObject\HttpRequestMethod;

class HttpTask extends Task
{
    #region FIELDS
    private HttpRequestMethod $method;
    private HttpHeaderHolder $headers;
    private HttpQueryHolder $queries;
    private HttpBodyHolder $bodies;
    #endregion

    #region CONSTRUCTOR
    public function __construct(int $id,string $name,string $address,string $description,HttpRequestMethod $method)
    {
        parent::__construct($id, $name, $address, $description);
        $this->setMethod($method);
        $this->headers = new HttpHeaderHolder();
        $this->queries = new HttpQueryHolder();
        $this->bodies = new HttpBodyHolder();
    }
    #endregion

    #region GETTER
    public function getMethod():HttpRequestMethod{
        return $this->method;
    }
    public function getHeaders(): HttpHeaderHolder
    {
        return $this->headers;
    }

    public function getQueries(): HttpQueryHolder
    {
        return $this->queries;
    }

    public function getBodies(): HttpBodyHolder
    {
        return $this->bodies;
    }
    #endregion

    #region SETTER
    public function setMethod(HttpRequestMethod $method): self{
        $this->method = $method;
        return $this;
    }
    public function setHeaders(HttpHeaderHolder $headers): void
    {
        $this->headers = $headers;
    }

    public function setQueries(HttpQueryHolder $queries): void
    {
        $this->queries = $queries;
    }

    public function setBodies(HttpBodyHolder $bodies): void
    {
        $this->bodies = $bodies;
    }
    #endregion

    #region ADDER
    public function addHeader(Parameter $header): void
    {
        $this->headers->addParameter($header);
    }

    public function addQuery(Parameter $query): void
    {
        $this->queries->addParameter($query);
    }

    public function addBody(Parameter $body): void
    {
        $this->bodies->addParameter($body);
    }
    #endregion

    #region CREATOR
    public function createHeaders(): array
    {
        return $this->headers->toHeader();
    }
    public function createQueries(): string
    {
        return $this->queries->toQuery();
    }
    public function createBodies(): array
    {
        return $this->bodies->toBody();
    }
    #endregion

    #region UTILS
    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "address" => $this->getAddress(),
            "description" => $this->getDescription(),
            "headers" => $this->headers->toArray(),
            "queries" => $this->queries->toArray(),
            "bodies" => $this->bodies->toArray(),
        ];
    }
    #endregion
}