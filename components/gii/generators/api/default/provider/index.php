<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";

?>

namespace <?= $generator->providersNamespace?>;

use app\models\search\<?= $generator->modelClass?>Search;

class <?= $generator->providerClass ?>
{

    /**
    * @param $params
    * @return array
    */
    public function search($params): array
    {
        $searchModel = new <?= $generator->modelClass?>Search();
        $dataProvider = $searchModel->search($params);
        return [$searchModel, $dataProvider];
    }
}