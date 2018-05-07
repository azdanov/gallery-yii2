<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the creation of table `feed`.
 */
class m180507_141010_create_feed_table extends Migration
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
            '{{%feed}}',
            [
                '[[id]]' => $this->primaryKey(),
                '[[user_id]]' => $this->integer(),
                '[[author_id]]' => $this->integer(),
                '[[author_name]]' => $this->string(),
                '[[author_nickname]]' => $this->integer(70),
                '[[author_picture]]' => $this->string(),
                '[[post_id]]' => $this->integer(),
                '[[post_filename]]' => $this->string()->notNull(),
                '[[post_description]]' => $this->text(),
                '[[post_created_at]]' => $this->integer()->notNull(),
            ],
            $tableOptions
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%feed}}');
    }
}
