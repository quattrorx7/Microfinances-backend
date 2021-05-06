<?php

use yii\db\Migration;

/**
 * Class m210506_220327_add_column_advance_old_user
 */
class m210506_220327_add_column_advance_old_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advance', 'user_id_old', $this->integer());

        $this->createIndex('idx_advance-user_id_old', 'advance', 'user_id_old');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advance', 'user_id_old');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210506_220327_add_column_advance_old_user cannot be reverted.\n";

        return false;
    }
    */
}
