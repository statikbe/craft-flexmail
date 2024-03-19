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

        $captcha = $request->getBodyParam('captcha', false);
        if ($captcha) {
            switch ($captcha) {
                case 'hCaptcha':
                    if (empty($_POST['h-captcha-response'])) {
                        return null;
                    }

                    $data = [
                        'secret' => getenv("HCAPTCHA_SECRET"),
                        'response' => $_POST['h-captcha-response']
                    ];
                    $verify = curl_init();
                    curl_setopt($verify, CURLOPT_URL,   "https://hcaptcha.com/siteverify");
                    curl_setopt($verify, CURLOPT_POST, true);
                    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                    $verifyResponse = curl_exec($verify);
                    $responseData = json_decode($verifyResponse);

                    if (!$responseData->success) {
                        return null;
                    }
                    break;

                case 'reCaptcha':
                    if (empty($_POST['g-recaptcha-response'])) {
                        return null;
                    }

                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = [
                        'secret'   => getenv("RECAPTCHA_SECRET_KEY"),
                        'response' => $_POST['g-recaptcha-response'],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ];

                    $options = [
                        'http' => [
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data)
                        ]
                    ];

                    $context  = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);

                    if (!json_decode($result)->success) {
                        return null;
                    }
                    break;

                default:

            }
        }


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
        $interests = $request->getBodyParam('interests', []);
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
                $interests,
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