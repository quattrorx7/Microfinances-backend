<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";

?>

namespace <?= $generator->exceptionsNamespace?>;

use app\components\exceptions\UserException;

class <?= $generator->modelClass?>NotFoundException extends UserException
{

}