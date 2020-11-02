<?php

namespace app\modules\admin\helpers;

use Yii;

class AdminMenu
{
    public static function getMenu(): array
    {
        return [
            ['label' => 'Основное', 'options' => ['class' => 'header']],
            [
                'label' => Yii::t('app', 'Users'),
                'icon'  => 'users',
                'url'   => ['/admin/user/index'],
                'items' => []
            ],
            [
                'label' => 'Клиенты',
                'icon'  => 'users',
                'url'   => ['/admin/client/index'],
                'items' => []
            ],
            [
                'label' => 'Районы',
                'icon'  => 'users',
                'url'   => ['/admin/district/index'],
                'items' => []
            ],
            [
                'label' => Yii::t('app', 'ApiLogger'),
                'icon'  => 'history',
                'url'   => ['/apiLogger/default/index'],
                'items' => []
            ],
            [
                'label' => Yii::t('app', 'Logout'),
                'icon'  => 'sign-out',
                'url' => ['/admin/auth/logout']
            ],
        ];
    }
}