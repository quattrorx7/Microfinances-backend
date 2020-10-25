<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m201019_182331_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'surname' => $this->string(),
            'patronymic' => $this->string(),
            'phone' => $this->string(),
            'additional_phone' => $this->string(),
            'residence_address' => $this->text(),
            'work_address' => $this->text(),
            'district_id' => $this->integer(),
            'owner_id' => $this->integer(),
            'created_at' => $this->dateTime()
        ]);

        $this->createIndex('idx_client-district_id', 'client', 'district_id');
        $this->createIndex('idx_client-owner_id', 'client', 'owner_id');

        $this->addForeignKey('fk_client-district_id',
            'client', 'district_id',
            'district', 'id'
        );
        $this->addForeignKey('fk_client-owner_id',
            'client', 'owner_id',
            'user', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
