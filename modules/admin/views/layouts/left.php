<aside class="main-sidebar">
    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => \app\modules\admin\helpers\AdminMenu::getMenu()
            ]
        ) ?>

    </section>

</aside>
