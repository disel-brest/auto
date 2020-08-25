<?php
use app\board\helpers\AutoServiceHelper;
use yii\helpers\Url;

/* @var $categories \app\board\entities\AutoServiceCategory[] */
/* @var $work_id int */

?>
<ul class="menu-list" id="services-menu-list">
    <?php foreach ($categories as $category) : ?>
        <li class="menu-list-item">
            <div class="menu-list-item-value">
                <span class="menu-list-item-icon"></span>
                <span class="menu-list-item-name"><?= $category->name ?>
                    <!-- <span class="count">(<?= AutoServiceHelper::getCountByCategoryID($category->id) ?>)</span> -->
                </span>
            </div>
            <ul class="dropdown-list">
                <?php foreach ($category->autoServiceWorks as $autoServiceWork) : ?>
                    <a href="<?= Url::to($work_id == $autoServiceWork->id ? ['/main/services/auto/auto-service/index'] : ['/main/services/auto/auto-service/index', 'work_id' => $autoServiceWork->id]) ?>" style="color: #868686">
                        <li class="dropdown-list-item<?= $work_id == $autoServiceWork->id ? ' active' : '' ?>">
                            <?= $autoServiceWork->name ?> <span class="count">(<?= AutoServiceHelper::getCountByWorkID($autoServiceWork->id) ?>)</span>
                        </li>
                    </a>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
