<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator \common\gii\generators\model\base\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

$intlTableSchema = Yii::$app->db->schema->getTableSchema($tableName.'_intl');
$intlColumns = null;
if ($intlTableSchema) {
    $intlColumns = $intlTableSchema->getColumnNames();
    $intlColumns = array_combine($intlColumns, $intlColumns);
    unset($intlColumns['id']);
    unset($intlColumns['model_id']);
    unset($intlColumns['lang_iso']);
    unset($intlColumns['is_active']);
}

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
<?php if ($intlColumns) { ?>

    use \common\components\IntlArTrait;

    /**
     * @return array
     */
    public static function getTranslateFieldsList()
    {
        return ['<?= implode("', '", $intlColumns); ?>'];
    }
<?php } ?>
<?php if (($queryClass = $generator->resolveQueryClassForModelClass($generator->ns.'\\'.$className)) != $generator->queryBaseClass): ?>

    /**
     * @inheritdoc
     * @return <?= $queryClass ?>

     */
    public static function find()
    {
        return new <?= $queryClass ?>(get_called_class());
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return <?= $generator->resolveQueryClassForModelClass($relation[1])?>

     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
}
