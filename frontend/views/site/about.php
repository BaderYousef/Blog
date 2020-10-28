<?php

/* @var $this yii\web\View */
/* @var $aboutMd \common\models\Post */

use yii\helpers\Markdown;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="body-content">
        <?= $aboutMd ? Markdown::process($aboutMd->content, 'gfm') : '<code>Please post a log titled about in the background, and the content will be displayed on this page.</code>' ?>
    </div>
</div>
