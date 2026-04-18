<?php

namespace Request\Builder;

require_once(__DIR__ . "/../HttpRequest.php");
require_once(__DIR__ . "/../../Task/HttpTask.php");
require_once(__DIR__ . "/../../Encoder/EncoderFactory.php");
require_once(__DIR__ . "/../../ValueObject/EncodeType.php");
require_once(__DIR__ . "/../../ValueObject/DataType.php");
require_once(__DIR__ . "/../../ValueObject/HttpRequestMethod.php");

use Exception;
use Request\HttpRequest;
use Task\HttpTask;
use Encoder\EncoderFactory;
use ValueObject\EncodeType;
use ValueObject\HttpRequestMethod;
use ValueObject\DataType;

class HttpRequestBuilder
{
    #region FIELDS
    private HttpRequestMethod $method = HttpRequestMethod::GET;
    private string      $url         = '';
    private array       $headers     = [];
    private array       $body        = [];
    private string      $queryString = '';
    private ?HttpTask   $task        = null;
    #endregion

    #region SETTERS
    public function withMethod(HttpRequestMethod $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function withUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /** Supply an HttpTask; headers, queries and bodies are sourced from it. */
    public function fromTask(HttpTask $task): static
    {
        $this->task = $task;
        return $this;
    }

    #endregion

    #region BUILD

    /**
     * Produce an immutable HttpRequest from the accumulated configuration.
     *
     * Processing order:
     *   1. Resolve URL (explicit > task address).
     *   2. Build headers from HttpHeaderHolder (encode values when needed).
     *   3. Build query string from HttpQueryHolder.
     *   4. Build body map from HttpBodyHolder (encode values when needed).
     */
    public function build(): HttpRequest
    {
        // ── 1. URL ──────────────────────────────────────────────────────────
        $url = $this->url;
        if ($url === '' && $this->task !== null) {
            $url = $this->task->getAddress();
        }
        if ($url === '') {
            throw new Exception("Request URL cannot be empty.");
        }

        // ── 2. Headers ──────────────────────────────────────────────────────
        $headers = $this->headers;
        if ($this->task !== null) {
            $headers = array_merge(
                $headers,
                $this->task->createHeaders()   // already "Name: value" strings
            );
        }

        // ── 3. Query string ─────────────────────────────────────────────────
        $queryString = $this->queryString;
        if ($this->task !== null) {
            $taskQuery = $this->task->createQueries();
            if ($taskQuery !== '') {
                $queryString = $queryString !== ''
                    ? $queryString . '&' . $taskQuery
                    : $taskQuery;
            }
        }

        // ── 4. Body ─────────────────────────────────────────────────────────
        $body = $this->body;
        if ($this->task !== null) {
            foreach ($this->task->createBodies() as $key => $parameter) {
                $body[$key] = $this->encodeParameterValue($parameter);
            }
        }

        return new HttpRequest($url, $this->method, $headers, $body, $queryString);
    }

    #endregion

    #region PRIVATE HELPERS

    /**
     * Encode a Parameter's value according to its DataType,
     * then apply any EncodeType transformation (base64 / hex).
     */
    private function encodeParameterValue(\Parameter\Parameter $parameter): mixed
    {
        $value      = $parameter->getValue();
        $dataType   = $parameter->getDataType();
        $encodeType = EncodeType::NONE; // default – may be extended later

        // Scalar coercion by DataType
        $value = match ($dataType) {
            DataType::INTEGER => (int)   $value,
            DataType::FLOAT   => (float) $value,
            DataType::BOOLEAN => (bool)  $value,
            DataType::JSON    => $this->ensureJson($value),
            default           => $value,
        };

        // Apply encoder when set
        if ($encodeType !== EncodeType::NONE) {
            $encoder = EncoderFactory::create($encodeType);
            return $encoder->encode(is_string($value) ? $value : (string)$value);
        }

        return $value;
    }

    private function ensureJson(mixed $value): string
    {
        if (is_string($value)) {
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }
        }
        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new Exception("Failed to JSON-encode body value: " . json_last_error_msg());
        }
        return $encoded;
    }

    #endregion
}