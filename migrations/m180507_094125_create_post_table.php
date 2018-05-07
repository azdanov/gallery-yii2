<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m180507_094125_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ('mysql' === $this->db->driverName) {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%post}}',
            [
                '[[id]]' => $this->primaryKey(),
                '[[user_id]]' => $this->integer()->notNull(),
                '[[filename]]' => $this->string()->notNull(),
                '[[description]]' => $this->text(),
                '[[created_at]]' => $this->integer()->notNull(),
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
