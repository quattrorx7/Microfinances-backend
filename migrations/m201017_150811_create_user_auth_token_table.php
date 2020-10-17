<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_auth_token}}`.
 */
class m201017_150811_create_user_auth_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_auth_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'push_token' => $this->string(),
            'auth_key' => $this->string()->notNull(),
            'device_id' => $this->string(),
            'status_id' => $this->smallInteger(1)->defaultValue(1)
        ]);
        
        $this->createIndex('idx_user_auth_token-user_id', 'user_auth_token', 'user_id');
        $this->addForeignKey('fk_user_auth_token-user_id',
            'user_auth_token', 'user_id',
            'user', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_auth_token}}');
    }
}
