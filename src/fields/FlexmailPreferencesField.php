<?php

namespace statikbe\flexmail\fields;

use craft\fields\Dropdown;
use Craft;
use statikbe\flexmail\Flexmail;


/**
 * @deprecated 3.0.0 Flexmail no longer supports using preferences, please switch to using Interests
 */
class FlexmailPreferencesField extends Dropdown
{

    /**
     * @inheritdoc
     */
    public bool $optgroups = false;

    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Preferences');
    }

    protected function options(): array
    {
        Craft::$app->deprecator->log("flexmail_preferences_field", "Preferences are deprecated in Flexmail. Please use Interests instead.", __CLASS__);
        try {

        $preferences = Flexmail::getInstance()->api->getPreferences();
        $data = [];
        $data[0]['value'] = '';
        $data[0]['label'] = Craft::t('flexmail', 'Select a preference group');
        foreach ($preferences['data']['_embedded']['item'] as $i) {
            $data[$i['id']]['value'] = $i['id'];
            $data[$i['id']]['label'] = $i['label'];
        }
        return $data;
        } catch (\Exception $e) {
            Craft::error($e->getMessage());
            return [];
        }
    }

    public function getSettingsHtml(): string
    {
        return false;
    }

}