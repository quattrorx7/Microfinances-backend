<?php

use yii\db\Migration;

/**
 * Class m201102_192352_alter_column
 */
class m201102_192352_alter_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('advance', 'limitation', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
