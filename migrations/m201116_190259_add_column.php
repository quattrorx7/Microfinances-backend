<?php

use yii\db\Migration;

/**
 * Class m201116_190259_add_column
 */
class m201116_190259_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'balance', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'balance');
    }
}
