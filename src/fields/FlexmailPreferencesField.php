<?php

namespace statikbe\flexmail\fields;

use craft\fields\Dropdown;
use Craft;
use statikbe\flexmail\Flexmail;

class FlexmailPreferencesField extends Dropdown
{

    /**
     * @inheritdoc
     */
    public $optgroups = false;

    protected function options(): array
    {

        $preferences = Flexmail::getInstance()->api->getPreferences();
        $data = [];
        $data[0]['value'] = '';
        $data[0]['label'] = Craft::t('flexmail', 'Select a preference group');
        foreach ($preferences['data'] as $i) {
            $data[$i['id']]['value'] = $i['id'];
            $data[$i['id']]['label'] = $i['label'];
        }
        return $data;
    }

    public function getSettingsHtml()
    {
        return false;
    }


    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Preferences');
    }

}