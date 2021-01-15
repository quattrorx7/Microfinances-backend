<?php

use yii\db\Migration;

/**
 * Class m210115_201931_add_column_payment_history
 */
class m210115_201931_add_column_payment_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_history', 'user_id', $this->integer());

        $this->createIndex('idx_payment_history-user_id', 'payment_history', 'user_id');

        $this->addForeignKey('fk_payment_history-user_id',
            'payment_history', 'user_id',
            'user', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_history', 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210115_201931_add_column_payment_history cannot be reverted.\n";

        return false;
    }
    */
}
