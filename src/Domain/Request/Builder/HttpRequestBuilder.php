<?php

namespace Request\Builder;

require_once(__DIR__ . "/../HttpRequest.php");
require_once(__DIR__ . "/../../Task/HttpTask.php");
require_once(__DIR__ . "/../../ValueObject/EncodeType.php");
require_once(__DIR__ . "/../../ValueObject/DataType.php");
require_once(__DIR__ . "/../../ValueObject/HttpRequestMethod.php");

use Task\HttpTask;
use Request\HttpRequest;

class HttpRequestBuilder
{
    #region BUILD
    /**
     */
    public function build(HttpTask $task): HttpRequest
    {
        $method = $task->getMethod();
        $url = $task->getAddress();
        $headers = $task->createHeaders();
        $queryString = $task->createQueries();
        $body = $task->createBodies();
        return new HttpRequest($url, $method, $headers, $body, $queryString);
    }
    #endregion
}