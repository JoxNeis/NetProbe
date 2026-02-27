<?php

namespace Model\Task;

require_once("../Task.php");

use MODEL\Task;

class HttpTask extends Task
{

    #region FIELDS
    private string $accept;
    private string $content_type;

    private array $custom_header;
    #endregion

    #region CONSTRUCTOR

    #endregion

    #region GETTER

    #endregion

    #region SETTER

    #endregion

    #region UTILS
    public function toArray()
    {
        $array = parent::toArray();
        $array['type'] = "HTTP";
        $array['accept'] = $this->accept;
        $array['content_type'] = $this->content_type;
        $array['custom_header'] = $this->custom_header;
    }
    #endregion

    #region LOGIC

    private function call_server(
        string $method
    ) {
        $ch = curl_init();

        $method = strtoupper($method);

        $defaultHeaders = [
            "Accept: " . $this->accept
        ];

        if ($this->content_type) {
            $defaultHeaders[] = "Content-Type: " . $this->content_type;
        }

        if ($method === "GET" && is_array($data)) {
            $url .= "?" . http_build_query($data);
            $data = null;
        }

        if (is_array($data) && $this->content_type === "application/json") {
            $data = json_encode($data);
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array_merge($defaultHeaders, $this->custom_header),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
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
}


?>