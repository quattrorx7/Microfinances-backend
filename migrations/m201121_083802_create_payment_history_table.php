<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_history}}`.
 */
class m201121_083802_create_payment_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_history}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer(),
            'advance_id' => $this->integer(),
            'client_id' => $this->integer(),
            'amount' => $this->integer(),
            'message' => $this->string()
        ]);

        $this->createIndex('idx_payment_history-payment_id', 'payment_history', 'payment_id');
        $this->createIndex('idx_payment_history-advance_id', 'payment_history', 'advance_id');
        $this->createIndex('idx_payment_history-client_id', 'payment_history', 'client_id');

        $this->addForeignKey('fk_payment_history-payment_id',
            'payment_history', 'payment_id',
            'payment', 'id'
        );
        $this->addForeignKey('fk_payment_history-advance_id',
            'payment_history', 'advance_id',
            'advance', 'id'
        );
        $this->addForeignKey('fk_payment_history-client_id',
            'payment_history', 'client_id',
            'client', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_history}}');
    }
}
