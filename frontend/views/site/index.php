<?php

/* @var $this yii\web\View */
/* @var $post common\models\Post */
/* @var $id integer */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = $id ? $post->title . ' - ' : '';
$this->title .= Yii::$app->name;
?>
<div class="site-index col-md-10">
    <?php if ($post): ?>
        <?php if ($post->archive_of_id): ?>
            <div class="notify-latest">
                <h3>This log is archived, please check the latest version</h3>
                <ul>
                    <li><?= Html::a(Yii::$app->getFormatter()->asDate($post->latest->created_at) . ': ' . $post->latest->title, Url::to(['', 'title' => $post->latest->title])) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="blog-header">
            <h1><?= $post->title ?></h1>
            <span>this log is created by : <?= $post->user->username ?>
             published on <?= Yii::$app->getFormatter()->asDatetime($post->created_at) ?></span>
            <span>
                <?php
                $tagHtmls = [];
                foreach ($post->tags as $tag) {
                    $tagHtmls[] = Html::a($tag->name, Url::to(['tag', 'tag' => $tag->name]));
                }
                ?>
                <?= $tagHtmls ? 'label: ' . join(', ', $tagHtmls) : '' ?>
            </span>
        </div>

        <div class="body-content">

            <?= $post->content ?>

        </div>

        <?php if (is_null($post->archive_of_id)): ?>
            <?php if ($post->archives): ?>
                <div class="notify-archives">
                    <h3>This log has the following historical versions：</h3>
                    <ul>
                        <?php foreach ($post->archives as $archivePost): ?>
                            <li><?= Html::a(Yii::$app->getFormatter()->asDate($archivePost->created_at) . ': ' . $archivePost->title, Url::to(['', 'id' => $archivePost->id])) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>


            <div class="body-footer">
                <div class="pull-left"><?= $post->nextPost ? Html::a('< New article：' . $post->nextPost->title, Url::to(['', 'title' => $post->nextPost->title]), ['class' => 'btn btn-success']) : '' ?></div>
                <div class="pull-right"><?= $post->prevPost ? Html::a('> Previous Article：' . $post->prevPost->title, Url::to(['', 'title' => $post->prevPost->title]), ['class' => 'btn btn-success']) : '' ?></div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <h3>There are no logs yet</h3>
    <?php endif; ?>
</div>
<div class="sidebar col-md-2">
    <?= $this->render('_sidebar') ?>
</div>