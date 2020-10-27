<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advance}}`.
 */
class m201027_171650_create_advance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advance}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(),
            'deleted_at' => $this->date(),
            'created_at' => $this->date(),
            'amount' => $this->integer(),
            'limitation' => $this->string(),
            'user_id' => $this->integer(),
            'status' => $this->string()
        ]);

        $this->createIndex('idx_advance-client_id', 'advance', 'client_id');
        $this->createIndex('idx_advance-user_id', 'advance', 'user_id');

        $this->addForeignKey('fk_advance-client_id',
            'advance', 'client_id',
            'client', 'id'
        );
        $this->addForeignKey('fk_advance-user_id',
            'advance', 'user_id',
            'user', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advance}}');
    }
}
