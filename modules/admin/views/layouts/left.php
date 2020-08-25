<?php

/* @var $directoryAsset string */

use app\rbac\Rbac;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->

        <!-- search form
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <?php
        $moderatorOptions = [
            ['label' => 'Пользователи', 'options' => ['class' => 'header']],
            ['label' => 'Жалобы', 'icon' => 'bug', 'url' => ['complaint/index']],
            //['label' => 'Жалобы на модераторов', 'icon' => 'fa fa-frown-o', 'url' => ['anti-warning/index']],
        ];

        $adminOptions = [
            ['label' => 'Объявления', 'options' => ['class' => 'header']],
            ['label' => 'Запчасти', 'icon' => 'gears', 'url' => ['part/index']],
            ['label' => 'Автомобили', 'icon' => 'car', 'url' => ['car/index']],
            ['label' => 'Шины', 'icon' => 'dot-circle-o', 'url' => ['tire/index']],
            ['label' => 'Диски', 'icon' => 'circle-o', 'url' => ['wheel/index']],

            ['label' => 'Автомобили', 'options' => ['class' => 'header']],
            ['label' => 'Марки', 'icon' => 'car', 'url' => ['auto-brand/index']],
            ['label' => 'Модели', 'icon' => 'info', 'url' => ['auto-model/index']],
            ['label' => 'Опции', 'icon' => 'ellipsis-v', 'url' => ['car-options/index']],

            ['label' => 'Шины', 'options' => ['class' => 'header']],
            ['label' => 'Производители', 'icon' => 'dot-circle-o', 'url' => ['tire-brand/index']],
            ['label' => 'Модели', 'icon' => 'info', 'url' => ['tire-model/index']],

            ['label' => 'Каталог услуг', 'options' => ['class' => 'header']],
            ['label' => 'Автосервисы', 'icon' => 'wrench', 'url' => ['auto-service/index']],
            ['label' => 'Виды работ', 'icon' => 'tasks', 'url' => ['auto-service-work/index']],

            ['label' => 'Пользователи', 'options' => ['class' => 'header']],
            ['label' => 'Все пользователи', 'icon' => 'users', 'url' => ['user/index']],
            ['label' => 'Сообщения', 'icon' => 'envelope', 'url' => ['ad-dialog/index']],

            ['label' => 'Прочее', 'options' => ['class' => 'header']],
            ['label' => 'Жалобы', 'icon' => 'bug', 'url' => ['complaint/index']],
            //['label' => 'Настройки', 'icon' => 'gear', 'url' => ['default/settings']],

            ['label' => 'Development', 'options' => ['class' => 'header'], 'visible' => YII_ENV_DEV],
            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'visible' => YII_ENV_DEV],
            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => YII_ENV_DEV],
        ];

        echo dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => Yii::$app->user->can(Rbac::PERMISSION_ADMIN) ? $adminOptions : $moderatorOptions
            ]
        ) ?>

    </section>

</aside>
