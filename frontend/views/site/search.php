<?php
/**
 * @author Pan Wenbin <panwenbin@gmail.com>
 */
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $s string */
/* @var $post \common\models\Post */

$this->title = 'search for: ' . $s;
?>

<div class="col-md-10">
    <h1><?= $this->title ?></h1>
    <?php if ($dataProvider->models): ?>
        <?php foreach ($dataProvider->models as $post): ?>
            <div class="blog-header">
                <h2><?= Html::a($post->title . ($post->isArchive() ? '[Archive]' : ''), $post->isArchive() ? Url::to(['index', 'id' => $post->id]) : Url::to(['index', 'title' => $post->title])) ?></h2>
                <span>This log is created by : <?= $post->user->username ?>
                    Published on <?= Yii::$app->getFormatter()->asDatetime($post->created_at) ?></span>
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
        <?php endforeach; ?>
    <?php else: ?>
        <h3>No log found</h3>
    <?php endif; ?>
</div>
<div class="sidebar col-md-2">
    <?= $this->render('_sidebar') ?>
</div>