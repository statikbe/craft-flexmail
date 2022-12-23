<?php


namespace statikbe\flexmail\utilities;

use Craft;
use craft\base\Utility;
use putyourlightson\blitzhints\BlitzHints;
use statikbe\flexmail\Flexmail;

class FlexmailUtility extends Utility
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Flexmail';
    }

    /**
     * @inheritdoc
     */
    public static function id(): string
    {
        return 'flexmail';
    }


    /**
     * @inheritdoc
     */
    public static function contentHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('flexmail/_utility', [
            'interests' => Flexmail::$plugin->interests->getInterests(),
        ]);
    }
}
