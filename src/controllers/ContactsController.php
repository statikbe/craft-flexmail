<?php

namespace statikbe\flexmail\controllers;

use craft\web\Controller;
use Craft;
use statikbe\flexmail\Flexmail;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ContactsController extends Controller
{
    protected $allowAnonymous = true;

    public function actionAdd()
    {
        $this->verifySettings();

        $request = Craft::$app->getRequest();
        $email = $request->getRequiredBodyParam('email');
        $firstName = $request->getBodyParam('firstName', null);
        $lastName = $request->getBodyParam('lastName', null);

        // TODO: Set language to Flexmail account default if not available?
        $language = $request->getBodyParam('language', 'en');
        $source = $request->getValidatedBodyParam('source', null);
        $fields = $request->getBodyParam('fields', []);
        $labels = $request->getBodyParam('labels', []);


        // Pass to service
        $preferences = $request->getBodyParam('preferences', []);

        try {
            $response = Flexmail::$plugin->contact->createOrUpdateContact(
                $email,
                $language,
                $source,
                $firstName,
                $lastName,
                $fields,
                $labels
            );
            if ($request->isJson) {
                return $this->asJson([
                    'success' => true,
                    'email' => $email
                ]);
            }

            return $this->redirectToPostedUrl();

        } catch (Exception $e) {
            if (Craft::$app->getConfig()->general->devMode) {
                throw new Exception($e->getMessage());
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
            throw new InvalidConfigException("API credentials not set");
        }
    }
}