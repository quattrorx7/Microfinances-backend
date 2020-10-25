<?php

use yii\db\Migration;

/**
 * Class m201025_064824_add_column
 */
class m201025_064824_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client', 'activity', $this->string());
        $this->addColumn('client', 'profit', $this->string());
        $this->addColumn('client', 'comment', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
