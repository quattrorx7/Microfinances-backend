<?php

use yii\db\Migration;

/**
 * Class m201229_071714_add_column_to_paymenthistory
 */
class m201229_071714_add_column_to_paymenthistory extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_history', 'type', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_history', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201229_071714_add_column_to_paymenthistory cannot be reverted.\n";

        return false;
    }
    */
}
