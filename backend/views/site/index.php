<?php

/* @var $this yii\web\View */
/* @var $numbers array */

use yii\helpers\Url;

$this->title = 'Console';
$this->title .= '- ' . Yii::$app->name;
?>
<div class="site-index">

    <a class="btn btn-primary" type="button" href="<?=Url::to(['post/index'])?>">
        Log <span class="badge"><?=$numbers['post']?></span>
    </a>

    <a class="btn btn-primary" type="button" href="<?=Url::to(['tag/index'])?>">
        label <span class="badge"><?=$numbers['tag']?></span>
    </a>
</div>