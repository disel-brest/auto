<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $this->title ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= Html::csrfMetaTags() ?>
    <?= Html::cssFile((YII_DEBUG ? '@web/css/all.css' : '@web/css/all.min.css') . '?v=' . filemtime(Yii::getAlias(YII_DEBUG ? '@webroot/css/all.css' : '@webroot/css/all.min.css'))) ?>
    <link rel="shortcut icon" href="/favicon.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <div class="container">
        <div class=="content" id="inner">
            <div class="company-wrap">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?= Html::jsFile((YII_DEBUG ? '@web/js/all.js' : '@web/js/all.min.js') . '?v=' . filemtime(Yii::getAlias(YII_DEBUG ? '@webroot/js/all.js' : '@webroot/js/all.min.js'))) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>