<?php

use yii\db\Migration;

/**
 * Class m210127_175345_add_column_to_refinancing
 */
class m210127_175345_add_column_to_refinancing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'refinancing', $this->boolean()->defaultValue(false));
        $this->addColumn('advance', 'refinancing_ids', $this->string()->defaultValue(null));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advance', 'refinancing');
        $this->dropColumn('advance', 'refinancing_ids');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210127_175345_add_column_to_refinancing cannot be reverted.\n";

        return false;
    }
    */
}
