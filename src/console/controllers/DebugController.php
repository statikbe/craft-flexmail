<?php

namespace statikbe\flexmail\console\controllers;

use craft\helpers\Console;
use statikbe\flexmail\Flexmail;
use yii\console\Controller;

class DebugController extends Controller
{
    public function actionGetSources() {
        $data = Flexmail::getInstance()->api->getSources();
        foreach($data as $source) {
            $this->stdout($source['id']);
            $this->stdout(' - ', Console::FG_GREY);
            $this->stdout($source['name'] . PHP_EOL, Console::FG_CYAN);
        }
    }
}