<?php

use yii\db\Migration;

/**
 * Class m201107_091421_add_column
 */
class m201107_091421_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'user_id', $this->integer());
        $this->addColumn('payment', 'district_id', $this->integer());

        $this->createIndex('idx_payment-user_id', 'payment', 'user_id');
        $this->createIndex('idx_payment-district_id', 'payment', 'district_id');

        $this->addForeignKey('fk_payment-user_id',
            'payment', 'user_id',
            'user', 'id'
        );
        $this->addForeignKey('fk_payment-district_id',
            'payment', 'district_id',
            'district', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201107_091421_add_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201107_091421_add_column cannot be reverted.\n";

        return false;
    }
    */
}
