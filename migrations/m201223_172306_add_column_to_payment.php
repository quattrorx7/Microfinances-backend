<?php

use yii\db\Migration;

/**
 * Class m201223_172306_add_column_to_payment
 */
class m201223_172306_add_column_to_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'full_amount', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment', 'amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201223_172306_add_column_to_payment cannot be reverted.\n";

        return false;
    }
    */
}
