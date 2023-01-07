<?php

namespace MojaHedi\Auth\Http\Api;



use Illuminate\Support\Facades\Http;
use MojaHedi\Auth\Logger\MojaHediLogger;

class SmsService implements MfaInterface
{

    private $params;
    private $apiUrl;

    public function __construct($params)
    {
        if (!extension_loaded('curl')) {
            die('cURL library is not loaded');
            exit;
        }

        $this->apiUrl = trim(config('mfa.sms_api_url'));
        $this->params = $params;
    }


    protected function execute()
    {
        $method = "GET";
        $params = $this->params;
        if (isset($params['method'])) {
            $method = $params['method'];
            unset($params['method']);
        }

        if ($method == "GET") {
            $response = Http::get($this->apiUrl, $params);
        } else {
            $headers = [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'charset: utf-8'
            ];

            $response = Http::withHeaders($headers)->post($this->apiUrl, $params);

        }
        if ($response->failed()) {
            MojaHediLogger::debug(print_r($params, true));
            MojaHediLogger::debug(print_r($response, true));
        }


    }

    public function send()
    {
        $this->execute();
    }


}
