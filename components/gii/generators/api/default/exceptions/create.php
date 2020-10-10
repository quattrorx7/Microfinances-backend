<?php

/** @var app\components\gii\generators\api\Generator $generator */

echo "<?php\n";

?>

namespace <?= $generator->exceptionsNamespace?>;

use app\components\exceptions\ValidateException;

class Validate<?= $generator->modelClass?>CreateException extends ValidateException
{

}