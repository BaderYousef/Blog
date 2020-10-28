<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log list';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <span class="pull-right"><a data-toggle="Hide search" href="#" onclick="(function(ele){$('.post-search').toggle(); var data = $(ele).attr('data-toggle'); $(ele).attr('data-toggle', $(ele).html()); $(ele).html(data);})(this);">Show search</a></span>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Write log', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'title',
            [
                'label' => 'label',
                'attribute' => 'user.tags',
                'value'=>function($model){
                    $tagName = [];
                    foreach ($model->tags as $tag){
                        $tagName[] = $tag->name;
                    }
                    $html = join('，',$tagName);
                    return $html;
                }
            ],
            'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>