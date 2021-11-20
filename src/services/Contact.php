<?php

namespace statikbe\flexmail\services;

use craft\base\Component;
use craft\helpers\Json;
use GuzzleHttp\Exception\BadResponseException;
use statikbe\flexmail\Flexmail;

class Contact extends Component
{
    public $api;

    private $baseUrl = 'https://api.flexmail.eu';


    public function init()
    {
        $this->api = Flexmail::getInstance()->api;
    }



    public function createOrUpdateContact($email, $language, $source = null, $firstName = null, $lastName = null, $customFields = [], $labels = [])
    {
//        TODO: Set default source in settings? Sources like lists?
        $fields = [
            'email' => $email,
            'first_name' => $firstName ?? null,
            'name' => $lastName ?? null,
            'language' => $language,
            'source' => (int)$source,
            'custom_fields' => $customFields,
        ];

        $response = $this->api->getContact($email);

        if (!$response['data']) {
            $body = Json::encode(array_filter($fields));
            $response = $this->api->sendRequest($this->baseUrl . '/contacts', $body, "POST");
        }

        if (!$response['links']['item']) {
            throw new BadResponseException("Resoucre not found");
        }

        $contact = $response['data'][0];
        $payload = $this->parseContact($contact, $fields);
        $hier = $this->api->sendRequest($response['links']['item'], Json::encode($payload), "PUT");


        // TODO preferences

        // TODO interest-labels


        return true;
    }

    private function parseContact($contact, $data)
    {
        $data = array_filter($data);

        foreach ($contact as $key => $value) {
            if (is_array($contact[$key])) {
                foreach ($contact[$key] as $k => $v) {
                    if (isset($data[$key][$k]) && $data[$key][$k]) {
                        $contact[$key][$k] = $data[$key][$k];
                    }
                }
            } else if (isset($data[$key]) && $data[$key]) {
                $contact[$key] = $data[$key];
            }
        }
        return $contact;
    }
}