<?php

namespace statikbe\flexmail\services;

use craft\base\Component;
use craft\helpers\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Header;
use GuzzleHttp\Psr7\Request;
use statikbe\flexmail\models\Contact;

class Api extends Component
{

    /**
     * Your flexmail API username
     * @var string
     */
    private $username;

    /**
     * Your flexmail API password (also called private token)
     * @var string
     */
    private $token;

    /**
     * Base url for the current flexmail API endpoint
     * @var string
     */
    private $baseUrl = 'https://api.flexmail.eu';

    public function init()
    {
        $this->username = getenv('FLEX_USER');
        $this->token = getenv('FLEX_TOKEN');
    }

    /**
     * @return array|null
     * @link https://api.flexmail.eu/documentation/#get-/account-contact-languages
     */
    public function getAccountLangauges()
    {
        return $this->sendRequest($this->baseUrl . "/account-contact-languages");
    }

    /**
     * @return array|mixed|void
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @link https://api.flexmail.eu/documentation/#get-/preferences
     */
    public function getPreferences()
    {
        $url = $this->baseUrl . '/preferences';
        return $this->sendRequest($url);
    }

    /**
     * @return array|mixed|void
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @link https://api.flexmail.eu/documentation/#get-/sources
     */
    public function getSources()
    {
        $url = $this->baseUrl . '/sources';
        return $this->sendRequest($url);
    }


    /**
     * @param string $uri
     * @param string $body
     * @param string $method
     * @return mixed|void
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendRequest($url, $body = null, $method = "GET")
    {
        try {
            $client = new Client();
            $request = new Request(
                $method,
                $url,
                [
                    "Authorization" => "Basic " . base64_encode("{$this->username}:{$this->token}")
                ],
                $body
            );

            $response = $client->sendRequest($request);
            if ($response->getStatusCode() < 400) {
                $parsed = Header::parse($response->getHeader('link'));
                $data = $response->getBody()->getContents();
                return [
                    'status' => $response->getStatusCode(),
                    'data' => Json::decodeIfJson($data),
                    'links' => $this->parseLinks($parsed),
                ];
            }
        } catch (ClientException $e) {
            \Craft::error($e->getMessage(), 'flexmail');
        }
    }

    private function parseLinks($data)
    {
        $links = [];
        foreach ($data as $i) {
            $link = str_replace("<", '', $i[0]);
            $link = str_replace(">", '', $link);
            $links[$i['rel']] = $link;
        }
        return $links;
    }
}