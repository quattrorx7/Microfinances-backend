<?php

use yii\db\Migration;

/**
 * Class m201223_131708_add_column_to_advance
 */
class m201223_131708_add_column_to_advance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'end_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advance', 'end_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201223_131708_add_column_to_advance cannot be reverted.\n";

        return false;
    }
    */
}
