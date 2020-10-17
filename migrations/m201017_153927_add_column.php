<?php

use yii\db\Migration;

/**
 * Class m201017_153927_add_column
 */
class m201017_153927_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'fullname', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
