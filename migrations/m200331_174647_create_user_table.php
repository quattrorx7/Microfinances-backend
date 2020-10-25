<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200331_174647_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->unique()->notNull(),
            'email_confirmed' => $this->smallInteger()->defaultValue(0),
            'auth_key'           => $this->string(32)->notNull(),
            'password_hash'      => $this->string()->notNull(),
            'confirmation_token' => $this->string(),
            'status'             => $this->smallInteger()->defaultValue(1),
            'superadmin'         => $this->smallInteger()->defaultValue(0),
            'created_at'         => $this->dateTime(),
            'updated_at'         => $this->dateTime(),
            'deleted_at'         => $this->dateTime(),
        ]);

        $this->createIndex('idx_user-email', '{{%user}}', 'email');
        $this->createIndex('idx_user-auth_key', '{{%user}}', 'auth_key');
        $this->createIndex('idx_user-superadmin', '{{%user}}', 'superadmin');
        $this->createIndex('idx_user-deleted_at', '{{%user}}', 'deleted_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
