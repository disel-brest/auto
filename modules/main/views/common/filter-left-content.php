<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

?>
<div class="select-category-block">
    <h2>Фильтра по категориям</h2>
    <div class="select-large-radius">
        <select name="select-category">
            <option value="">АВТО</option>
            <option value="">Прочее...</option>
        </select>
    </div>
    <div class="select-large-radius">
        <select name="select-subcategory">
            <!-- <option value="1">Легковые авто</option> -->
            <option value="2">Автозапчасти</option>
            <option value="3">Шины</option>
            <option value="4">Диски</option>
        </select>
    </div>
</div>
