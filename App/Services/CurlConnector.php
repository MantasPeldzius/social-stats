<?php
namespace App\Services;

class CurlConnector
{
    private $url;
    private $config;
    private $token;
    
    public function __construct($config)
    {
        $this->config = $config;
        $this->url = $this->config['url'];
    }
    
    public function init()
    {
        $token = $this->requestToken();
        if ($token === false) {
            throw new \Exception('Couldn\'t get Token');
        } else {
            $this->token = $token;
        }
    }
    
    public function fetchPosts($page = 1)
    {
        $response = $this->makeRequest(
            $this->config['actions']['posts']['action'],
            $this->config['actions']['posts']['method'],
            ['sl_token' => $this->token, $page]
        );
        if ($this->validateResponse($response)) {
            return $this->getPostsFromResponse($response);
        } else {
            return false;
        }
    }
    
    private function requestToken()
    {
        $response = $this->makeRequest(
            $this->config['actions']['token']['action'],
            $this->config['actions']['token']['method'],
            $this->config['actions']['token']['params']
        );
        if ($this->validateResponse($response)) {
            return $this->getTokenFromResponse($response);
        } else {
            return false;
        }
    }
    
    private function getTokenFromResponse($response)
    {
        $response = json_decode($response);
        if (isset($response->data->sl_token)) {
            return $response->data->sl_token;
        } else {
            return false;
        }
    }
    
    private function getPostsFromResponse($response)
    {
        $response = json_decode($response);
        if (isset($response->data->posts)) {
            return $response->data->posts;
        } else {
            return false;
        }
    }
    
    private function validateResponse($response)
    {
        if ($response === false) {
            return false;
        }
        $response = json_decode($response);
        if ($response === null || !is_object($response)) {
            return false;
        }
        if (isset($response->error) || !isset($response->data)) {
            return false;
        }
        return true;
    }
    
    private function makeRequest($action, $method, $params = [])
    {
        switch ($method) {
            case 'get':
                return $this->makeGetRequest($action, $params);
            case 'post':
                return $this->makePostRequest($action, $params);
            default:
                return $this->makeGetRequest($action, $params);
        }
    }
    
    private function makePostRequest($action, $params)
    {
        $url = $this->url . $action;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }
    
    private function makeGetRequest($action, $params)
    {
        $url = $this->url . $action . '?' . http_build_query($params);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ]);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }
}

