<?php

namespace statikbe\flexmail\services;

use craft\base\Component;
use craft\helpers\App;
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
use statikbe\flexmail\records\InterestRecord;

class Interests extends Component
{

    /**
     * @return InterestRecord[]
     * Get all interests stored in the local database
     */
    public function getInterests()
    {
        return InterestRecord::find()->all();
    }

    /**
     * @return InterestRecord[]
     * Update local interests with data from the API
     */
    public function refresh(): void
    {
        $interests = Flexmail::$plugin->api->getInterests();
        $collection = collect($interests['data']['_embedded']['item']);
        foreach ($collection as $item) {
            $record = InterestRecord::findOne(['uid' => $item['id']]);
            if (!$record) {
                $record = new InterestRecord();
                $record->setAttribute('uid', $item['id']);
            }
            $record->setAttribute('label', $item['name']);
            $record->save();
        }
    }

}
