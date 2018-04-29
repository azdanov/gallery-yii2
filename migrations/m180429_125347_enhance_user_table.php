<?php

use yii\db\Migration;

/**
 * Class m180429_125347_enhance_user_table.
 */
class m180429_125347_enhance_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'about', $this->text());
        $this->addColumn('{{%user}}', 'type', $this->integer(3));
        $this->addColumn('{{%user}}', 'nickname', $this->string(70));
        $this->addColumn('{{%user}}', 'picture', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'picture');
        $this->dropColumn('{{%user}}', 'nickname');
        $this->dropColumn('{{%user}}', 'type');
        $this->dropColumn('{{%user}}', 'about');
    }
}
