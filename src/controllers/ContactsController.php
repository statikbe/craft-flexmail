<?php

namespace statikbe\flexmail\controllers;

use craft\web\Controller;
use Craft;
use statikbe\flexmail\Flexmail;
use yii\base\Exception;

class ContactsController extends Controller
{
    protected $allowAnonymous = true;

    public function actionAdd()
    {
        $request = Craft::$app->getRequest();
        $email = $request->getRequiredBodyParam('email');
        $firstName = $request->getBodyParam('firstName', null);
        $lastName = $request->getBodyParam('lastName', null);

        // TODO: Set language to Flexmail account default if not available?
        $language = $request->getBodyParam('language', 'en');
        $source = $request->getValidatedBodyParam('source', null);
        $fields = $request->getBodyParam('fields', []);

        // Pass to service
        $preferences = $request->getBodyParam('preferences', []);

        try {
            $response = Flexmail::$plugin->contact->createOrUpdateContact(
                $email,
                $language,
                $source,
                $firstName,
                $lastName,
                $fields
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
}