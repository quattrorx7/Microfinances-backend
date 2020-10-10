<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminModuleAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets';

    public $css = [
        'css/admin.css',
        'css/highlight.css'
    ];
    public $js = [
        'js/highlight.js',
        'js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}