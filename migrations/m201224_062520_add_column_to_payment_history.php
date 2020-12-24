<?php

use yii\db\Migration;

/**
 * Class m201224_062520_add_column_to_payment_history
 */
class m201224_062520_add_column_to_payment_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_history', 'debt', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_history', 'debt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201224_062520_add_column_to_payment_history cannot be reverted.\n";

        return false;
    }
    */
}
