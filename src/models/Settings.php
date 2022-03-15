<?php

namespace statikbe\flexmail\models;


use craft\base\Model;

class Settings extends Model
{
    public string $apiUsername= '';

    public string $apiToken = '';

    public int $defaultSource = 0;

    public function rules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['apiUsername', 'apiToken'], 'required'];
        return $rules;
    }


}