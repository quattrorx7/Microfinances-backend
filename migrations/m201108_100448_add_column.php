<?php

use yii\db\Migration;

/**
 * Class m201108_100448_add_column
 */
class m201108_100448_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'payment_left', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

}
