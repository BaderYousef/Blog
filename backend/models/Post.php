<?php
namespace backend\models;


class Post extends \common\models\Post
{
    public $makeOldAsArchive;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['makeOldAsArchive'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'makeOldAsArchive' => 'Release as a new version, archive the old version',
        ]);
    }

    public function beforeValidate()
    {
        $this->title = str_replace('/', '', $this->title);
        return parent::beforeValidate();
    }
}
