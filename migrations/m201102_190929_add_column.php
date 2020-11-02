<?php

use yii\db\Migration;

/**
 * Class m201102_190929_add_column
 */
class m201102_190929_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'issue_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
