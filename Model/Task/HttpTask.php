<?php

namespace Model\Task;

require_once("../Task.php");

use Model\Task;
use Exception;

class HttpTask extends Task
{

    #region CONSTANTS

    private const METHODS = [
        "GET",
        "POST",
        "PUT",
        "DELETE",
        "PATCH",
        "HEAD",
        "OPTIONS"
    ];

    #endregion


    #region FIELDS

    private array $data = [];

    private mixed $processed_data = null;

    private array $configs = [
        "method" => "GET",
        "timeout" => 30,
        "connect_timeout" => 10,
        "follow_location" => true,
        "header" => [
            "accept" => "*/*",
            "content_type" => null,
            "custom" => []
        ]
    ];

    #endregion


    #region CONSTRUCTOR

    public function __construct()
    {
    }

    #endregion


    #region GETTERS

    public function getData(): array
    {
        return $this->data;
    }

    public function getMethod(): string
    {
        return $this->configs["method"];
    }

    public function getAccept(): string
    {
        return $this->configs["header"]["accept"];
    }

    public function getContentType(): ?string
    {
        return $this->configs["header"]["content_type"];
    }

    public function getHeaders(): array
    {
        return $this->configs["header"];
    }

    public function getTimeout(): int
    {
        return $this->configs['timeout'];
    }

    public function getConnectTimeout(): int
    {
        return $this->configs['connect_timeout'];
    }


    public function getFollowLocation(): bool
    {
        return $this->configs['follow_location'];
    }
    public function getConfig(): array
    {
        return $this->configs;
    }
    #endregion


    #region SETTERS

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setMethod(string $method): self
    {
        $method = strtoupper($method);

        if (!in_array($method, self::METHODS, true)) {
            throw new Exception("Invalid HTTP method");
        }

        $this->configs["method"] = $method;

        return $this;
    }

    public function setAccept(string $accept): self
    {
        $this->configs["header"]["accept"] = $accept;
        return $this;
    }

    public function setContentType(?string $type): self
    {
        $this->configs["header"]["content_type"] = $type;
        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->configs["timeout"] = $timeout;
        return $this;
    }
    public function setConnectTimeout(int $connect_timeout): self
    {
        $this->configs["connect_timeout"] = $connect_timeout;
        return $this;
    }

    public function setFollowLocation(bool $follow_location): self
    {
        $this->configs['follow_location'] = $follow_location;
        return $this;
    }

    public function addHeader(string $key, string $value): self
    {
        $this->configs["header"]["custom"][$key] = $value;
        return $this;
    }
    #endregion


    #region HEADER BUILDER

    private function buildHeaders(): array
    {
        $headers = [];

        if ($this->getAccept()) {
            $headers[] = "Accept: " . $this->getAccept();
        }

        if ($this->getContentType()) {
            $headers[] = "Content-Type: " . $this->getContentType();
        }

        foreach ($this->configs["header"]["custom"] as $k => $v) {
            $headers[] = "$k: $v";
        }

        return $headers;
    }

    #endregion


    #region CURL CALL

    private function call_server(
        string $method,
        array|string|null $data
    ): array {

        $ch = curl_init();

        $this->setMethod($method);

        $url = $this->getAddress();

        if ($method === "GET" && is_array($data)) {
            $url .= "?" . http_build_query($data);
            $data = null;
        }

        if (is_array($data) && $this->getContentType() === "application/json") {
            $data = json_encode($data);
        }

        $headers = $this->buildHeaders();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->configs['timeout'],
            CURLOPT_CONNECTTIMEOUT => $this->configs['connect_timeout'],
            CURLOPT_FOLLOWLOCATION => $this->configs['follow_location'],
        ]);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($ch);

        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        
        return [
            "status" => $httpCode,
            "error" => $error,
            "body" => $response
        ];
    }

    #endregion


    #region EXECUTE

    public function execute(): array
    {
        $result = $this->call_server(
            $this->getMethod(),
            $this->getData()
        );

        return $result;
    }

    #endregion


    #region UTILS

    public function toArray(): array
    {
        $array = parent::toArray();

        $array["type"] = "HTTP";
        $array["config"] = $this->configs;
        $array["data"] = $this->data;

        return $array;
    }

    #endregion

}