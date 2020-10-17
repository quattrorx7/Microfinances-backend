<?php

use yii\db\Migration;

/**
 * Class m201015_193651_alter
 */
class m201015_193651_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'email', $this->string()->null());
        $this->alterColumn('user', 'auth_key', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
