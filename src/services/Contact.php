<?php

namespace statikbe\flexmail\services;

use craft\base\Component;
use craft\helpers\Json;
use GuzzleHttp\Exception\BadResponseException;
use statikbe\flexmail\Flexmail;

class Contact extends Component
{
    /**
     * @var Api
     */
    public $api;

    private string $baseUrl = 'https://api.flexmail.eu';

    private $contact;


    public function init(): void
    {
        $this->api = Flexmail::getInstance()->api;
    }



    public function createOrUpdateContact($email, $language, $source = null, $firstName = null, $lastName = null, $customFields = [], $interests = [], $labels = [], $preferences = [], int $optInFormId = 0)
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

        $response = $this->api->searchContactByEmail($email);

        if (!isset($response['data']['_embedded'])) {
            if ($optInFormId) {
                unset($fields['source']);
                $optinFields = array_merge($fields, ['opt_in_form_id' => $optInFormId]);
                $body = Json::encode(array_filter($optinFields));
                $response = $this->api->optinContact($body);
                return true;
            }
            $body = Json::encode(array_filter($fields));
            $response = $this->api->addContact($body);
            if(!isset($response['data']['_embedded'])) {
                $response = $this->api->searchContactByEmail($email);
                $this->contact = $response['data']['_embedded']['item'][0];
            } else {
                $this->contact = $response['data']['_embedded']['item'][0];
            }
        } else {
            $this->contact = $response['data']['_embedded']['item'][0];
        }

        if (!$this->contact) {
            throw new BadResponseException("Resource not found");
        }

        $payload = $this->parseContact($this->contact, $fields);
        $response = $this->api->updateContact($this->baseUrl . $this->contact['_links']['self']['href'], Json::encode($payload));

        if($interests) {
            $this->api->addInterestsToContact($this->contact, $interests);
        }

        if($labels) {
            $this->api->addInterestLabelsToContact($this->contact, $labels);
        }

        if($preferences) {
            $this->api->addPreferencesToContact($this->contact, $preferences);
        }

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