<?php

/** @var app\components\gii\generators\model\work\Generator $generator */

echo "<?php\n";
?>

namespace <?= $generator->ns?>;

/**
 * Class <?= $generator->modelClass?>

 * @package <?= $generator->ns?>

 */
class <?= $generator->modelClass?> extends <?= $generator->baseModelsNs.'\\'.$generator->modelClass?>

{

}