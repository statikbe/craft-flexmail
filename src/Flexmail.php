<?php
/**
 * Flexmail for Craft CMS
 *
 *
 * @link      https://www.statik.be
 * @copyright Copyright (c) 2021 Statik
 */

namespace statikbe\flexmail;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use statikbe\flexmail\fields\FlexmailInterestLabelsField;
use statikbe\flexmail\fields\FlexmailPreferencesField;
use statikbe\flexmail\services\Api;
use statikbe\flexmail\services\Contact;
use yii\base\Event;

/**
 *
 * @property Api api
 * @property Contact contact
 */
class Flexmail extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Flexmail
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = FlexmailPreferencesField::class;
                $event->types[] = FlexmailInterestLabelsField::class;
            }
        );

        $this->setComponents([
            'api' => Api::class,
            'contact' => Contact::class,
        ]);
    }
}
