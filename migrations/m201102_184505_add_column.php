<?php

use yii\db\Migration;

/**
 * Class m201102_184505_add_column
 */
class m201102_184505_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'daily_payment', $this->integer()->defaultValue(null));
        $this->addColumn('advance', 'summa_with_percent', $this->integer()->defaultValue(null));
        $this->addColumn('advance', 'percent', $this->float(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
