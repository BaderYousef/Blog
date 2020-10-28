<?php
/**
 * @author Pan Wenbin <panwenbin@gmail.com>
 */
use common\models\Tag;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<?= Html::beginForm(['site/search'], 'get', ['class' => "form-inline"]) ?>
<div class="form-group">
    <label class="sr-only" for="s">search for</label>
    <input type="text" class="form-control" id="s" name="s" placeholder="search for">
</div>
<?= Html::endForm() ?>

<div class="tags">
    <h3>Tag search</h3>
    <div>
        <?php foreach (Tag::tagsWithFontSize() as $tag): ?>
            <?= Html::a($tag['name'], Url::to(['site/tag', 'tag' => $tag['name']]), ['style' => "font-size: {$tag['fontSize']}"]) ?>
        <?php endforeach; ?>
    </div>
</div>