<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;

class GeneratorController extends Controller
{
    public $modelNamespace = 'app\\models';

    /**
     * @var string namespace path for crud controller
     */
    public $crudControllerNamespace = 'app\\modules\\crud\\controllers';

    /**
     * @var string namespace path for crud search models
     */

    public $crudSearchModelNamespace = 'app\\models\\search';

    /**
     * @var string namespace path for crud views
     */
    public $crudViewPath = '@app/modules/crud/views';

    public $createModels = true;

    public function options($id)
    {
        return array_merge(
            parent::options($id),
            [
                'createModels'
            ]
        );
    }

    /**
     *
     * @param string $table
     *
     * @return int
     * @throws \yii\console\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionCrud($table = ''): int
    {
        $this->stdout("Generating CRUD in backend\n");
        $config       = $this->getYiiConfiguration();
        $config['id'] = 'tmp-console';
        $connection = Yii::$app->db;

        /** @var \yii\db\mysql\Schema **/
        $dbSchema = $connection->schema;
        $tables = $dbSchema->getTableNames();
        $totalTables = count($tables);

        Console::startProgress(0, $totalTables);
        $app  = \Yii::$app;
        FileHelper::createDirectory(\Yii::getAlias('@app/modules/crud/controllers'));
        FileHelper::createDirectory(\Yii::getAlias('@app/modules/crud/views'));
        FileHelper::createDirectory(\Yii::getAlias('@app/models/search'));

        foreach($tables as $k=>$tbl)
        {
            if(!empty($table) && $table!=$tbl){
                continue;
            }
            if(mb_strpos($tbl, 'off_')!==false){
                continue;
            }
            $modelClass = Inflector::id2camel($tbl, '_');
            $name   = Inflector::camelize($tbl);
            if(!class_exists($this->modelNamespace . '\\' . $name) && empty($table)){
                continue;
            }

            $tblDir = str_replace("_", '-', $tbl);
            echo $tbl . ' class[' . $modelClass .']' . PHP_EOL;
            $params = [
                'interactive'         => 0,
                'overwrite'           => !empty($table),
                'modelClass'          => $this->modelNamespace . '\\' . $name,
                'searchModelClass'    => $this->crudSearchModelNamespace . '\\' . $name.'Search',
                'controllerClass'     => $this->crudControllerNamespace . '\\' . $name . 'Controller',
                'viewPath'            => $this->crudViewPath . DIRECTORY_SEPARATOR . $tblDir,
                'enableI18N'          => false,
                'messageCategory'     => 'app',
                'baseControllerClass' =>  'app\components\controllers\AuthedAdminController'
            ];
            $temp = new \yii\console\Application($config);
            $temp->runAction('gii/crud', $params);
            unset($temp);
            Console::updateProgress(($k+1), $totalTables);
        }
        \Yii::$app = $app;
        Console::endProgress();
        return 0;
    }

    public function actionMod($table = ''): int
    {
        $this->println('Generating services and repositories');
        $tables = $table ? [$table] : array_filter(Yii::$app->db->schema->getTableNames(), function ($tableName) {
            return !(mb_strpos($tableName, 'auth_') === 0 || mb_strpos($tableName, 'off_') === 0 || $tableName === 'migration');
        });

        $totalTables = count($tables);
        Console::startProgress(0, $totalTables);
        $i = 0;

        foreach ($tables as $index => $tableName) {
            $modelClass = Inflector::id2camel($tableName, '_');
            $modelLowerCamelCase = Inflector::variablize($modelClass);

            $params = [
                'modelClass' => $modelClass,
                'moduleNamespace' => 'app\\modules\\' . Inflector::variablize($modelClass) . '\\components',
                'repositoryClass' => $modelClass . 'Repository',
                'factoryClass' => $modelClass . 'Factory',
                'serviceClass' => $modelClass . 'Service',
                'modelLowerCamelCase' => $modelLowerCamelCase
            ];

            $modulePath = \Yii::getAlias("@app/modules/$modelLowerCamelCase/components");

            FileHelper::createDirectory($modulePath);

            if(!file_exists($modulePath . "/{$modelClass}Repository.php")) {
                $this->run('/gii/mod', $params);
            }

            if(!file_exists($modulePath . "/{$modelClass}Factory.php")) {
                $this->run('/gii/mod', $params);
            }

            if(!file_exists($modulePath . "/{$modelClass}Service.php")) {
                $this->run('/gii/mod', $params);
            }

            $i++;
            Console::updateProgress($i, $totalTables);
        }

        Console::endProgress();
        return 0;
    }

    public function actionApi($table = ''): int
    {
        $this->println('Generating module for api');
        $tables = $table ? [$table] : array_filter(Yii::$app->db->schema->getTableNames(), function ($tableName) {
            return !(mb_strpos($tableName, 'auth_') === 0 || mb_strpos($tableName, 'off_') === 0 || $tableName === 'migration');
        });

        $totalTables = count($tables);
        Console::startProgress(0, $totalTables);
        $i = 0;

        foreach ($tables as $index => $tableName) {
            $modelClass = Inflector::id2camel($tableName, '_');
            $modelLowerCamelCase = Inflector::variablize($modelClass);

            $params = [
                'modelClass' => $modelClass,
                'moduleNamespace' => 'app\\modules\\' . Inflector::variablize($modelClass) . '\\components',
                'exceptionsNamespace' => 'app\\modules\\' . Inflector::variablize($modelClass) . '\\exceptions',
                'formsNamespace' => 'app\\modules\\' . Inflector::variablize($modelClass) . '\\forms',
                'providersNamespace' => 'app\\modules\\' . Inflector::variablize($modelClass) . '\\providers',
                'apiNamespace' => 'app\\modules\\api\\controllers',
                'serializerNamespace' => 'app\\modules\\api\\serializer\\'.Inflector::variablize($modelClass),
                'repositoryClass' => $modelClass . 'Repository',
                'factoryClass' => $modelClass . 'Factory',
                'createFormClass' => $modelClass . 'CreateForm',
                'updateFormClass' => $modelClass . 'UpdateForm',
                'providerClass' => $modelClass . 'Provider',
                'createExceptionClass' => 'Validate' . $modelClass . 'CreateException',
                'updateExceptionClass' => 'Validate' . $modelClass . 'UpdateException',
                'notFoundExceptionClass' => $modelClass . 'NotFoundException',
                'serviceClass' => $modelClass . 'Service',
                'populatorClass' => $modelClass . 'Populator',
                'apiControllerClass' => $modelClass . 'Controller',
                'serializerClass' => $modelClass . 'Serializer',
                'modelLowerCamelCase' => $modelLowerCamelCase
            ];

            $modulePath = \Yii::getAlias("@app/modules/$modelLowerCamelCase/components");
            $exceptionsPath = \Yii::getAlias("@app/modules/$modelLowerCamelCase/exceptions");
            $formsPath = \Yii::getAlias("@app/modules/$modelLowerCamelCase/forms");
            $providersPath = \Yii::getAlias("@app/modules/$modelLowerCamelCase/providers");
            $serializerPath = \Yii::getAlias("@app/modules/api/serializer/$modelLowerCamelCase");

            FileHelper::createDirectory($modulePath);
            FileHelper::createDirectory($exceptionsPath);
            FileHelper::createDirectory($formsPath);
            FileHelper::createDirectory($providersPath);
            FileHelper::createDirectory($serializerPath);

            $this->run('/gii/api', $params);

            $i++;
            Console::updateProgress($i, $totalTables);
        }

        Console::endProgress();
        return 0;
    }

    public function actionModels($table = ''): int
    {
        $this->println('Generating base db models (ActiveRecord)');

        if (!$this->createModels) {
            return 0;
        }

        $tables = $table ? [$table] : array_filter(Yii::$app->db->schema->getTableNames(), function ($tableName) {
            return !(mb_strpos($tableName, 'auth_') === 0 || mb_strpos($tableName, 'off_') === 0 || $tableName === 'migration');
        });

        $totalTables = count($tables);

        Console::startProgress(0, $totalTables);

        $i = 0;

        foreach ($tables as $index => $tableName) {
            $modelClass = Inflector::id2camel($tableName, '_');
            $this->println("{$tableName} class[{$modelClass}]");

            $this->run('/gii/model-base', [
                'tableName'   => $tableName,
                'modelClass'  => $modelClass,
                'interactive' => 0,
                'overwrite'   => true,
            ]);

            if(!file_exists(Yii::getAlias("@app/models/{$modelClass}.php"))){
                $this->run('/gii/model-work', [
                    'modelClass' => $modelClass,
                    'interactive' => 0,
                    'overwrite' => false,
                ]);
            }
            $i++;
            Console::updateProgress($i, $totalTables);
        }

        Console::endProgress();
        return 0;
    }

    protected function println($string)
    {
        $this->stdout($string . "\r");
    }

    protected function getYiiConfiguration()
    {
        if (isset($GLOBALS['config'])) {
            $config = $GLOBALS['config'];
        } else {
            $config = require(\Yii::getAlias('@app') . '/config/web.php');
        }

        return $config;
    }
}
