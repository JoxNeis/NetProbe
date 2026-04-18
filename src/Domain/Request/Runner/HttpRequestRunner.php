<?php

namespace Request\Runner;

require_once(__DIR__ . "/../HttpRequest.php");

use Exception;
use CurlHandle;
use Request\HttpRequest;
use ValueObject\HttpRequestMethod;

class HttpRequestRunner
{
    #region CONFIGURATION FIELDS
    private int $timeout = 30;
    private int $connectTimeout = 10;
    private bool $verifySsl = true;
    private bool $followRedirects = true;
    private int $maxRedirects = 5;
    #endregion

    #region FLUENT CONFIGURATION

    public function withTimeout(int $seconds): static
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function withConnectTimeout(int $seconds): static
    {
        $this->connectTimeout = $seconds;
        return $this;
    }

    public function withSslVerification(bool $verify): static
    {
        $this->verifySsl = $verify;
        return $this;
    }

    public function withFollowRedirects(bool $follow, int $max = 5): static
    {
        $this->followRedirects = $follow;
        $this->maxRedirects = $max;
        return $this;
    }

    #endregion

    #region RUN

    /**
     * Execute the request and return a structured result array.
     *
     * @throws Exception on cURL initialisation failure or transport error.
     */
    public function run(HttpRequest $request): array
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new Exception("Failed to initialise cURL handle.");
        }

        try {
            $this->configure($ch, $request);
            return $this->execute($ch);
        } finally {
            curl_close($ch);
        }
    }

    #endregion

    #region PRIVATE HELPERS

    private function configure(CurlHandle $ch, HttpRequest $request): void
    {
        $method = $request->getMethod();
        $fullUrl = $request->getFulladdress();

        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySsl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->verifySsl ? 2 : 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->followRedirects);
        curl_setopt($ch, CURLOPT_MAXREDIRS, $this->maxRedirects);

        match ($method) {
            HttpRequestMethod::GET => null, // default
            HttpRequestMethod::POST => $this->applyPost($ch, $request),
            HttpRequestMethod::PUT => $this->applyPut($ch, $request),
            HttpRequestMethod::PATCH => $this->applyPatch($ch, $request),
            HttpRequestMethod::DELETE => curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'),
            HttpRequestMethod::HEAD => curl_setopt($ch, CURLOPT_NOBODY, true),
            default => $this->applyCustomMethod($ch, $method, $request),
        };

        $headers = $request->getHeaders();
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
    }

    private function applyPost(CurlHandle $ch, HttpRequest $request): void
    {
        curl_setopt($ch, CURLOPT_POST, true);
        $body = $request->getBody();
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->serializeBody($body));
        }
    }

    private function applyPut(CurlHandle $ch, HttpRequest $request): void
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $body = $request->getBody();
        if (!empty($body)) {
            $serialized = $this->serializeBody($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $serialized);
        }
    }

    private function applyPatch(CurlHandle $ch, HttpRequest $request): void
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $body = $request->getBody();
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->serializeBody($body));
        }
    }

    private function applyCustomMethod(
        CurlHandle $ch,
        HttpRequestMethod $method,
        HttpRequest $request
    ): void {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $body = $request->getBody();
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->serializeBody($body));
        }
    }

    /**
     * Decide whether to send as JSON or form-encoded.
     * If all values are scalar the body is form-encoded; otherwise JSON.
     */
    private function serializeBody(array $body): string
    {
        $allScalar = array_reduce(
            $body,
            fn(bool $carry, mixed $v) => $carry && is_scalar($v),
            true
        );

        return $allScalar
            ? http_build_query($body)
            : json_encode($body, JSON_UNESCAPED_UNICODE);
    }

    private function execute(\CurlHandle $ch): array
    {
        $raw = curl_exec($ch);

        if ($raw === false) {
            throw new Exception(
                "cURL error (" . curl_errno($ch) . "): " . curl_error($ch)
            );
        }

        $info = curl_getinfo($ch);
        $headerSize = $info['header_size'];
        $rawHeaders = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);

        return [
            'status' => (int) $info['http_code'],
            'headers' => $this->parseHeaders($rawHeaders),
            'body' => $body,
            'info' => $info,
        ];
    }

    private function parseHeaders(string $raw): array
    {
        $headers = [];
        foreach (explode("\r\n", $raw) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, 'HTTP/')) {
                continue;
            }
            [$name, $value] = array_pad(explode(':', $line, 2), 2, '');
            $headers[trim($name)] = trim($value);
        }
        return $headers;
    }

    #endregion
}