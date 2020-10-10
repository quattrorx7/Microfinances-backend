<?php

namespace app\models\query;

use app\components\traits\ActiveQueryDeleteTrait;
use app\models\User;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[User]].
 * *
 * @see User
 */

class UserQuery extends ActiveQuery
{

    use ActiveQueryDeleteTrait;

}