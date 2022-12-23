<?php

namespace statikbe\flexmail\fields;

use Craft;
use craft\fields\Dropdown;
use statikbe\flexmail\Flexmail;

/**
 * @deprecated 3.0.0 Flexmail no longer supports using preferences, please switch to using Interests
 */
class FlexmailInterestLabelsField extends Dropdown
{

    /**
     * @inheritdoc
     */
    public bool $optgroups = false;

    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Interest Labels');
    }

    protected function options(): array
    {
        Craft::$app->deprecator->log("flexmail_interest_labels_field", "Interest Labels are deprecated in Flexmail. Please use Interests instead.", __CLASS__);

        try {

            $labels = Flexmail::getInstance()->api->getInterestLabels();
            $data = [];
            $data[0]['value'] = '';
            $data[0]['label'] = Craft::t('flexmail', 'Select an interest label');
            foreach ($labels['data']['_embedded']['item'] as $i) {
                $data[$i['id']]['value'] = $i['id'];
                $data[$i['id']]['label'] = $i['name'];
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