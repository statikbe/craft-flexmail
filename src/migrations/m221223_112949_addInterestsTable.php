<?php

namespace statikbe\flexmail\migrations;

use craft\db\Migration;
use statikbe\flexmail\Flexmail;
use statikbe\flexmail\records\InterestRecord;

/**
 * m221223_112949_addInterestsTable migration.
 */
class m221223_112949_addInterestsTable extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable(
            InterestRecord::$tableName, [
            'id' => $this->primaryKey(),
            'label' => $this->string(100),
            'uid' => $this->uid()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
        ]);

        Flexmail::$plugin->interests->refresh();
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m221223_112949_addInterestsTable cannot be reverted.\n";
        return false;
    }
}
