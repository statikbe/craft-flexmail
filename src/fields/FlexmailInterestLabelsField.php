<?php

namespace statikbe\flexmail\fields;

use Craft;
use craft\fields\Dropdown;
use statikbe\flexmail\Flexmail;

class FlexmailInterestLabelsField extends Dropdown
{

    /**
     * @inheritdoc
     */
    public $optgroups = false;

    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Interest Labels');
    }

    protected function options(): array
    {
        try {

            $labels = Flexmail::getInstance()->api->getInterestLabels();
            $data = [];
            $data[0]['value'] = '';
            $data[0]['label'] = Craft::t('flexmail', 'Select an interest label');
            foreach ($preferences['data']['_embedded']['item'] as $i) {
                $data[$i['id']]['value'] = $i['id'];
                $data[$i['id']]['label'] = $i['name'];
            }
            return $data;
        } catch (\Exception $e) {
            Craft::error($e->getMessage());
            return [];
        }
    }

    public function getSettingsHtml()
    {
        return false;
    }
}