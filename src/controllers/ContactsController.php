<?php

namespace statikbe\flexmail\controllers;

use craft\web\Controller;
use Craft;
use statikbe\flexmail\Flexmail;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ContactsController extends Controller
{
    protected bool|array|int $allowAnonymous = true;

    public function actionAdd()
    {
        $this->verifySettings();

        $request = Craft::$app->getRequest();
        $email = $request->getRequiredBodyParam('email');
        $firstName = $request->getBodyParam('firstName', null);
        $lastName = $request->getBodyParam('lastName', null);

        $language = $request->getBodyParam('language', $this->parseLocale());
        $source = $request->getValidatedBodyParam('source', '');
        if (!$source) {
            $source = Flexmail::getInstance()->getSettings()->defaultSource;
        }

        if (!$source) {
            throw new InvalidConfigException("Flexmail source not defined");
        }

        $fields = $request->getBodyParam('fields', []);
        $labels = $request->getBodyParam('labels', []);
        $preferences = $request->getBodyParam('preferences', []);

        try {
            $response = Flexmail::$plugin->contact->createOrUpdateContact(
                $email,
                $language,
                $source,
                $firstName,
                $lastName,
                $fields,
                $labels,
                $preferences
            );
            if ($request->isJson) {
                return $this->asJson([
                    'success' => true,
                    'email' => $email
                ]);
            }

            return $this->redirectToPostedUrl();

        } catch (\Exception $e) {
            if (Craft::$app->getConfig()->general->devMode) {
                throw $e;
            } else {
                Craft::error($e->getMessage());
            }
        }

    }

    private function verifySettings()
    {
        $username = Flexmail::getInstance()->getSettings()->apiUsername;
        $token = Flexmail::getInstance()->getSettings()->apiToken;
        if (!$username || !$token) {
            throw new InvalidConfigException("Flexmail API credentials not set");
        }
    }

    private function parseLocale()
    {
        $locale = Craft::$app->getSites()->getCurrentSite()->language;
        $str = explode('-', $locale);
        return $str[0];
    }
}