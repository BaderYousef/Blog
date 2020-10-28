<?php

use panwenbin\yii2\simplemde\widgets\SimpleMDE;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagNames')->widget(\kartik\select2\Select2::className(), [
        'data' => ArrayHelper::map(\common\models\Tag::find()->asArray()->all(), 'name', 'name'),
        'options' => ['placeholder' => 'add tag', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'content')->textarea() ?>

    <?php if ($model->isNewRecord == false): ?>
        <?= $model->isArchive() ? '<p class="bg-warning">It is not recommended to modify the archive, unless there are unavoidable reasons</p>' : $form->field($model, 'makeOldAsArchive')->checkbox() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Post log' : 'Update log', ['class' => $model->isNewRecord == false ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>