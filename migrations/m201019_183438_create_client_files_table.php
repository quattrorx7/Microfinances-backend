<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_file}}`.
 */
class m201019_183438_create_client_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(null),
            'full_path' => $this->text()->defaultValue(null),
            'path' => $this->string()->defaultValue(null),
            'title' => $this->string()->defaultValue(null),
            'filename' => $this->string()->defaultValue(null),
            'mimetype' => $this->string()->defaultValue(null),
            'hash' => $this->string()->defaultValue(null),
            'size' => $this->integer(),
            'type' => $this->string()->defaultValue(null),
            'created_at' => $this->dateTime()->comment('Дата добавления'),
            'deleted_at' => $this->dateTime()->comment('Дата удаления')
        ]);
        $this->createIndex('idx_file_parent_id', 'file', 'parent_id');
        $this->addForeignKey('fk_file_parent_id',
            'file', 'parent_id',
            'file', 'id',
            'no action', 'no action'
        );

        $this->createTable('{{%client_file}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(),
            'file_id' => $this->integer()
        ]);

        $this->createIndex('idx_client_file-client_id', 'client_file', 'client_id');
        $this->createIndex('idx_client_file-file_id', 'client_file', 'file_id');

        $this->addForeignKey('fk_client_file-client_id',
            'client_file', 'client_id',
            'client', 'id'
        );
        $this->addForeignKey('fk_client_file-file_id',
            'client_file', 'file_id',
            'file', 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file}}');
        $this->dropTable('{{%client_file}}');
    }
}
