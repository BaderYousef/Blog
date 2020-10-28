<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Log list', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <?php if ($model->archive_of_id): ?>
        <div class="notify-latest">
            <h3>This log is archived, please check the latest version：</h3>
            <ul>
                <li><?= Html::a(Yii::$app->getFormatter()->asDate($model->latest->created_at) . ': ' . $model->latest->title, Url::to(['', 'id' => $model->latest->id])) ?></li>
            </ul>
        </div>
    <?php endif; ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update log', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete log', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this log?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user.username',
                'label' => 'Author',
            ],
            'title',
            [
                'attribute' => 'tagNames',
                'value' => join(',', $model->getTagNames()),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php if (is_null($model->archive_of_id)): ?>
        <?php if ($model->archives): ?>
            <div class="notify-archives">
                <h3>This log has the following historical versions：</h3>
                <ul>
                    <?php foreach ($model->archives as $archivePost): ?>
                        <li><?= Html::a(Yii::$app->getFormatter()->asDate($archivePost->created_at) . ': ' . $archivePost->title, Url::to(['', 'id' => $archivePost->id])) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>