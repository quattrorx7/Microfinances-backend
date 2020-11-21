<?php

use yii\db\Migration;

/**
 * Class m201121_091537_add_column
 */
class m201121_091537_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_history', 'created_at', $this->dateTime());
        $this->addColumn('payment_history', 'actor', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
