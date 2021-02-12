<?php

use yii\db\Migration;

/**
 * Class m210211_132528_add_column_notify_to_users
 */
class m210211_132528_add_column_notify_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'token', $this->string()->defaultValue(null));
        $this->addColumn('user', 'notification', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'token');
        $this->dropColumn('user', 'notification');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210211_132528_add_column_notify_to_users cannot be reverted.\n";

        return false;
    }
    */
}
