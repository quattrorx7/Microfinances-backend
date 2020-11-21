<?php

use yii\db\Migration;

/**
 * Class m201117_193701_alter_column
 */
class m201117_193701_alter_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('advance', 'issue_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('advance', 'issue_date', $this->date());
    }
}
