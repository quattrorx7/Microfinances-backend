<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m201105_183248_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'advance_id' => $this->integer(),
            'client_id' => $this->integer(),
            'amount' => $this->integer(),
            'created_at' => $this->date()
        ]);

        $this->createIndex('idx_payment-client_id', 'payment', 'client_id');
        $this->createIndex('idx_payment-advance_id', 'payment', 'advance_id');

        $this->addForeignKey('fk_payment-advance_id',
            'payment', 'advance_id',
            'advance', 'id'
        );
        $this->addForeignKey('fk_payment-client_id',
            'payment', 'client_id',
            'client', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment}}');
    }
}
