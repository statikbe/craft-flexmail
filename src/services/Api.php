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
use statikbe\flexmail\Flexmail;
use statikbe\flexmail\models\Contact;
use Craft;

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
        $this->username = Craft::parseEnv(Flexmail::getInstance()->getSettings()->apiUsername);
        $this->token = Craft::parseEnv(Flexmail::getInstance()->getSettings()->apiToken);
    }


    /**
     * @param $email
     * @return array|mixed|void
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @link https://api.flexmail.eu/documentation/#get-/contacts
     */
    public function searchContactByEmail($email)
    {
        $url = $this->baseUrl . "/contacts?" . http_build_query(['email' => $email]);
        return $this->sendRequest($url);
    }

    /**
     * @param $data
     * @return mixed
     * @link https://api.flexmail.eu/documentation/#post-/contacts
     */
    public function addContact($data)
    {
        return $this->sendRequest($this->baseUrl . '/contacts', $data, "POST");
    }

    /**
     * @link https://api.flexmail.eu/documentation/#put-/contacts/-id-
     */
    public function updateContact($link, $data)
    {
        return $this->sendRequest($link, $data, "PUT");
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
     * @link https://api.flexmail.eu/documentation/#get-/interest-labels
     */
    public function getInterestLabels()
    {
        $url = $this->baseUrl . '/interest-labels';
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

    public function addInterestLabelToContact($contact, $labels)
    {
        foreach ($labels as $label) {
            try {

            $body = [
                'contact_id' => (int)$contact['id'],
                'interest_label_id' => (int)$label
            ];
            $response = $this->sendRequest($this->baseUrl . '/contact-interest-label-subscriptions', Json::encode($body), "POST");
            } catch (\Exception $e) {
                if(!$e->getResponse()->getStatusCode() === 409) {
                    throw $e;
                }
            }
        }

    }


    /**
     * @param string $uri
     * @param string $body
     * @param string $method
     * @return mixed|void
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function sendRequest($url, $body = null, $method = "GET")
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
            } else {
                throw new ClientException($response->getReasonPhrase(), $request, $response);
            }
        } catch (ClientException $e) {
            throw $e;
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