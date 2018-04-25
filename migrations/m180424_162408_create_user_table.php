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
        $this->createTable(
            'user',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull(),
                'auth_key' => $this->string()->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string()->notNull(),
                'email' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ]
        );

        $this->createTable(
            'auth',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'source' => $this->string()->notNull(),
                'source_id' => $this->string()->notNull(),
            ]
        );

        $this->addForeignKey(
            'fk-auth-user_id-user-id',
            'auth',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth');
        $this->dropTable('user');
    }
}
