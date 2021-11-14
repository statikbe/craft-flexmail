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

        $this->setComponents([
            'api' => Api::class,
            'contact' => Contact::class,
        ]);
    }
}
