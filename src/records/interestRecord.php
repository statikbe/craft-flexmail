<?php

namespace statikbe\flexmail\records;

use craft\db\ActiveRecord;

class InterestRecord extends ActiveRecord
{
    // Props
    // =========================================================================

    public static $tableName = '{{%flexmail_interests}}';

    /**
     * @inheritdoc
     *
     * @return string
     */
    public static function tableName (): string
    {
        return self::$tableName;
    }
}