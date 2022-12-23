<?php

namespace statikbe\flexmail\controllers;

use craft\web\Controller;
use Craft;
use statikbe\flexmail\Flexmail;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class InterestsController extends Controller
{
    protected bool|array|int $allowAnonymous = false;

    public function actionRefresh()
    {
        Flexmail::$plugin->interests->refresh();
        return $this->redirectToPostedUrl();
    }
}