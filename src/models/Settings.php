<?php

namespace statikbe\flexmail\models;


use craft\base\Model;

class Settings extends Model
{
    public $apiUsername;

    public $apiToken;

    public $defaultSource;

    public function rules()
    {
        $rules = parent::defineRules();
        $rules[] = [['apiUsername', 'apiToken'], 'required'];
        return $rules;
    }


}