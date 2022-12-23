<?php

namespace statikbe\flexmail\fields;

use Craft;
use craft\fields\Dropdown;
use statikbe\flexmail\Flexmail;

class FlexmailInterestsField extends Dropdown
{

    /**
     * @inheritdoc
     */
    public bool $optgroups = false;

    public static function displayName(): string
    {
        return Craft::t('flexmail', 'Flexmail Interests');
    }

    protected function options(): array
    {
        try {

            $labels = Flexmail::getInstance()->interests->getInterests();
            $data = [];
            $data[0]['value'] = '';
            $data[0]['label'] = Craft::t('flexmail', 'Select an interest');
            foreach ($labels as $i) {
                $data[$i['id']]['value'] = $i['uid'];
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