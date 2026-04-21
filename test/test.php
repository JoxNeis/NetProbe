<?php

header("Content-Type: application/json");
// Ensure errors are displayed for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoloading / Requiring necessary dependencies based on your folder structure
require_once __DIR__ . '/../src/Domain/ValueObject/HttpRequestMethod.php';
require_once __DIR__ . '/../src/Domain/Request/HttpRequest.php';
require_once __DIR__ . '/../src/Domain/Response/HttpResponse.php';
require_once __DIR__ . '/../src/Domain/Request/Runner/HttpRequestRunner.php';

use ValueObject\HttpRequestMethod;
use Request\HttpRequest;
use Request\Runner\HttpRequestRunner;
use Response\HttpResponse;

class HttpRequestRunnerTester
{
    private HttpRequestRunner $runner;

    public function __construct()
    {
        // Initialize the runner with fluent configurations
        $this->runner = (new HttpRequestRunner())
            ->withTimeout(15)
            ->withConnectTimeout(5)
            ->withSslVerification(false) 
            ->withFollowRedirects(true, 3);
    }

    public function runAllTests()
    {
        $this->testGetRequest();
        $this->testPostRequest();
    }

    private function testGetRequest()
    {
        echo "========================================\n";
        echo "--- Testing GET Request ---\n";
        echo "========================================\n";
        
        $request = new HttpRequest(
            'http://localhost/is-apache-up',
            HttpRequestMethod::GET,
            ['Accept: application/json', 'X-Custom-Header: NetProbe'],
            [], // Body is empty for GET
            'test=123&query=hello' // Query strings
        );

        try {
            $response = $this->runner->run($request);
            $this->printResponse($response);
        } catch (Exception $e) {
            echo "❌ GET Request Failed: " . $e->getMessage() . "\n";
        }
    }

    private function testPostRequest()
    {
        echo "========================================\n";
        echo "--- Testing POST Request (JSON) ---\n";
        echo "========================================\n";
        
        // Based on your HttpRequestRunner logic, if any array value is non-scalar, 
        // it serialises the body as JSON rather than form-encoded string.
        $body = [
            'app_name' => 'NetProbe',
            'version' => 1.0,
            'metadata' => [
                'test_type' => 'automated'
            ]
        ];

        $request = new HttpRequest(
            'http://localhost/is-apache-up',
            HttpRequestMethod::POST,
            ['Content-Type: application/json'],
            $body,
            ''
        );

        try {
            $response = $this->runner->run($request);
            $this->printResponse($response);
        } catch (Exception $e) {
            echo "❌ POST Request Failed: " . $e->getMessage() . "\n";
        }
    }

    private function printResponse(HttpResponse $response)
    {
        echo "🟢 Status Code: " . $response->getHttpStatusCode() . "\n\n";
        
        echo "🔵 Headers:\n";
        foreach ($response->getHeader() as $key => $value) {
            echo "  $key: $value\n";
        }
        echo "\n";

        echo "🟣 Body:\n";
        $body = $response->getBody();
        
        $decoded = json_decode($body);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo json_encode($decoded, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo $body . "\n";
        }
        echo "\n";
    }
}

// Execute the tests
$tester = new HttpRequestRunnerTester();
$tester->runAllTests();