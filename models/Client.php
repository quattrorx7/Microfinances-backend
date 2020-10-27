<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class Client
 * @package app\models
 *
 * @property-write File $file
 * @property-read ActiveQuery $files
 */
class Client extends \app\models\base\Client
{

    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(File::class, ['id' => 'file_id'])
            ->via('clientFiles');
    }

    public function setFile(array $files): void
    {
        $this->populateRelation('files', $files);
    }

    public function getActiveAdvances(): ActiveQuery
    {
        return $this->hasMany(Advance::class, ['client_id' => 'id'])->andOnCondition(['IS', 'deleted_at', null]);
    }
}