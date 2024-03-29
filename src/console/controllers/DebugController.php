<?php

namespace statikbe\flexmail\console\controllers;

use craft\helpers\Console;
use statikbe\flexmail\Flexmail;
use yii\console\Controller;

class DebugController extends Controller
{
    public function actionGetSources()
    {
        $response = Flexmail::getInstance()->api->getSources();
        foreach ($response['data']['_embedded']['item'] as $i) {
            $this->stdout($i['id']);
            $this->stdout(' - ', Console::FG_GREY);
            $this->stdout($i['name'] . PHP_EOL, Console::FG_CYAN);
        }
        return true;
    }

    public function actionGetInterests()
    {
        $response = Flexmail::getInstance()->api->getInterests();
        foreach ($response['data']['_embedded']['item'] as $i) {
            $this->stdout($i['id']);
            $this->stdout(' - ', Console::FG_GREY);
            $this->stdout($i['name'] . PHP_EOL, Console::FG_CYAN);
        }
        return true;
    }

}