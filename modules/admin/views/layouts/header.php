<?php

use app\components\constants\UrlConst;
use app\modules\admin\helpers\AdminLinksHelper;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><i class="fa fa-list"></i></span><span class="logo-lg">' . Yii::$app->name . '</span>', UrlConst::DEFAULT_ADMIN_PAGE, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown messages-menu">
                    <a href="<?= AdminLinksHelper::getMailCatcherLink() ?>" title="Mailcatcher">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><i class="fa fa-question"></i></span>
                    </a>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="<?= AdminLinksHelper::getSupervisorLink() ?>" title="Supervisor">
                        <i class="fa fa-eye"></i>
                        <span class="label label-warning"><i class="fa fa-question"></i></span>
                    </a>
                </li>
                <li class="dropdown tasks-menu">
                    <a href="<?= AdminLinksHelper::getRabbitLink() ?>" title="Rabbit">
                        <i class="fa fa-paw"></i>
                        <span class="label label-danger"><i class="fa fa-question"></i></span>
                    </a>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?= Yii::$app->user->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header"></li>
                        <!-- Menu Body -->
<!--                        <li class="user-body">-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Followers</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Sales</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Friends</a>-->
<!--                            </div>-->
<!--                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
<!--                                <a href="#" class="btn btn-default btn-flat">Profile</a>-->
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    [UrlConst::LOGOUT_ADMIN_PAGE],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
