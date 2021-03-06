<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180424_162408_create_user_table extends Migration
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
            '{{%user}}',
            [
                '[[id]]' => $this->primaryKey(),
                '[[username]]' => $this->string()->notNull()->unique(),
                '[[auth_key]]' => $this->string(32)->notNull(),
                '[[password_hash]]' => $this->string()->notNull(),
                '[[password_reset_token]]' => $this->string()->unique(),
                '[[email]]' => $this->string()->notNull()->unique(),
                '[[status]]' => $this->smallInteger()->notNull()->defaultValue(10),
                '[[created_at]]' => $this->integer()->notNull(),
                '[[updated_at]]' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%auth}}',
            [
                '[[id]]' => $this->primaryKey(),
                '[[user_id]]' => $this->integer()->notNull(),
                '[[source]]' => $this->string()->notNull(),
                '[[source_id]]' => $this->string()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            '[[fk-auth-user_id-user-id]]',
            '{{%auth}}',
            '[[user_id]]',
            '{{%user}}',
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user}}');
    }
}
