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

    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Preferences');
    }

    protected function options(): array
    {
        try {

        $preferences = Flexmail::getInstance()->api->getPreferences();
        $data = [];
        $data[0]['value'] = '';
        $data[0]['label'] = Craft::t('flexmail', 'Select a preference group');
        foreach ($preferences['data'] as $i) {
            $data[$i['id']]['value'] = $i['id'];
            $data[$i['id']]['label'] = $i['label'];
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