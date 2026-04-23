<?php

namespace Request\Builder;

use CURLFile;

require_once(__DIR__ . "/../HttpRequest.php");
require_once(__DIR__ . "/../../Task/HttpTask.php");
require_once(__DIR__ . "/../../ValueObject/EncodeType.php");
require_once(__DIR__ . "/../../ValueObject/DataType.php");
require_once(__DIR__ . "/../../ValueObject/HttpRequestMethod.php");

use Parameter\HttpHolder\HttpHeaderHolder;
use Parameter\HttpHolder\HttpBodyHolder;
use Parameter\HttpHolder\HttpQueryHolder;
use Task\HttpTask;
use Request\HttpRequest;
use ValueObject\DataType;
use ValueObject\HttpHeaderCategory;
use ValueObject\Http\HttpBodyType;

class HttpRequestBuilder
{

    #region FIELD
    private HttpHeaderHolder $headers;
    private HttpQueryHolder $queries;
    private HttpBodyHolder $bodies;

    #endregion
    #region BUILD
    public function build(HttpTask $task): HttpRequest
    {
        $method = $task->getMethod();
        $url = $task->getAddress();
        $this->headers = $task->getHeaders();
        $this->queries = $task->getQueries();
        $this->bodies = $task->getBodies();
        return new HttpRequest($url, $method, $this->createHeaders(), $this->createBodies(), $this->createQueries());
    }
    #endregion

    #region CREATOR

    public function createHeaders(): array
    {
        $header = [];
        foreach ($this->headers->getParameters() as $parameter) {
            $header[] =
                $parameter->getKey()->value .
                ": " .
                $parameter->getValue();
        }
        return $header;
    }
    public function createQueries(): string
    {
        $queryArray = [];
        foreach ($this->queries->getParameters() as $parameter) {
            $queryArray[
                $parameter->getKey()
            ] = $parameter->getValue();
        }
        return http_build_query($queryArray);
    }
    public function createBodies(): string|array
    {
        $contentTypeParam = $this->headers->getParameters()[HttpHeaderCategory::CONTENT_TYPE->value] ?? null;
        $bodyType = $contentTypeParam?->getValue();

        $data = [];
        foreach ($this->bodies->getParameters() as $parameter) {
            $key = $parameter->getKey();

            if ($parameter->getDataType() === DataType::FILE) {
                $data[$key] = new CURLFile($parameter->getValue());
            } else {
                $data[$key] = $parameter->getModifiedValue();
            }
        }

        return match (true) {
            $bodyType === HttpBodyType::FORM_MULTIPART => $data,          
            $bodyType === HttpBodyType::JSON => json_encode($data, JSON_UNESCAPED_UNICODE),
            $bodyType === HttpBodyType::FORM_URLENCODED => http_build_query($data),
            default => $data,          
        };
    }
    #endregion
}