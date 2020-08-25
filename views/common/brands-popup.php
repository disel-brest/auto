<?php

use app\helpers\AutoHelper;
use app\helpers\CounterHelper;

?>
<div id="select-mark-pop-up" class="mfp-hide popup-wrap">
    <p class="popup-title">Выберите марку</p>
    <div class="select_mark_pop-up_content">
        <ul>
        <?php
        foreach (AutoHelper::getBrands() as $brand) {
            ?>
            <li>
                <a href="javascript:void(0)" data-id="<?= $brand->id ?>">
                    <span class="mark_auto"><?= $brand->name ?>
                        <?= CounterHelper::spanCounter($brand->id, CounterHelper::TYPE_CAR_BRAND) ?>
                    </span>
                </a>
            </li>
            <?php
        }
        ?>
        </ul>
        <button class="all_marks show-all-btn">Любая</button>
    </div>
</div>