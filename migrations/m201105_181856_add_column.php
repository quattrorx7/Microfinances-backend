<?php

use yii\db\Migration;

/**
 * Class m201105_181856_add_column
 */
class m201105_181856_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'payment_status', $this->smallInteger(1)->defaultValue(0));
        $this->addColumn('advance', 'summa_left_to_pay', $this->integer()->defaultValue(null));

        $this->createIndex('idx_advance-payment_status', 'advance', 'payment_status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
