<?php
namespace common\models;


use common\models\gii\PostGii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class Post
 * @package common\models
 * @property User $user
 * @property Post $nextPost
 * @property Post $prevPost
 * @property Post[] $archives
 * @property Post $latest
 * @property PostTagRelation[] $postTagRelations
 * @property Tag[] $tags
 */
class Post extends PostGii
{
    public $makeOldAsArchive;
    protected $tagNames; // Array of tags used to receive submissions

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ],
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['tagNames', 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'tagNames' => 'label',
        ]);
    }

    /**
     * Is it an archive
     * @return bool
     */
    public function isArchive()
    {
        return $this->archive_of_id != null;
    }

    /**
     * Whether it is an archive read the received tag array, otherwise read the old data tag array
     * @return array
     */
    public function getTagNames()
    {
        if ($this->tagNames) return $this->tagNames;
        return ArrayHelper::getColumn($this->tags, 'name');
    }

    public function setTagNames($tagNames)
    {
        $this->tagNames = $tagNames;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /**
         * deal with tag
         */
        if ($this->tagNames) {
            $tagNames = $this->tagNames;

            /* Compare the labels to be created */
            $existsTags = Tag::findAll(['name' => $tagNames]);
            $existsTagNames = ArrayHelper::getColumn($existsTags, 'name');
            $createTagNames = array_udiff($tagNames, $existsTagNames, function ($tagName, $existsTagName) {
                return strcmp(strtolower($tagName), strtolower($existsTagName)); // Database is not case sensitive
            });

            /* @var $createdTags Tag[] New label created */
            $createdTags = [];
            foreach ($createTagNames as $createTagName) {
                $createTag = new Tag();
                $createTag->name = $createTagName;
                $createTag->save();
                $createTag->setIsNewRecord(false); // link need
                $createdTags[] = $createTag;
            }
            /* @var $newTags Tag[] Updated label */
            $newTags = array_merge($existsTags, $createdTags);
            $newTagIds = ArrayHelper::getColumn($newTags, 'id');

            /* @var $oldTags Tag[] Label before update */
            $oldTags = $this->tags;
            $oldTagIds = ArrayHelper::getColumn($oldTags, 'id');

            /* Delete label relationship */
            foreach ($oldTags as $oldTag) {
                if (in_array($oldTag->id, $newTagIds) == false) {
                    $this->unlink('tags', $oldTag, true);
                }
            }

            /* Add label relationship */
            foreach ($newTags as $createTag) {
                if (in_array($createTag->id, $oldTagIds) == false) {
                    $this->link('tags', $createTag);
                }
            }
        }
    }

    /**
     * Get the user who posted this log
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return PostQuery
     */
    public function getNextPost()
    {
        return self::find()->andWhere(['>', 'created_at', $this->created_at])->andWhere(['archive_of_id' => null])->andWhere(['!=','title','about'])->orderBy('created_at ASC')->limit(1);
    }

    /**
     * @return PostQuery
     */
    public function getPrevPost()
    {
        return self::find()->andWhere(['<', 'created_at', $this->created_at])->andWhere(['archive_of_id' => null])->andWhere(['!=','title','about'])->orderBy('created_at DESC')->limit(1);
    }

    /**
     * Get the old archive list of this new log
     * @return \yii\db\ActiveQuery
     */
    public function getArchives()
    {
        return $this->hasMany(Post::className(), ['archive_of_id' => 'id'])->inverseOf('latest');
    }

    /**
     * Get the latest version log of this archive log
     * @return \yii\db\ActiveQuery
     */
    public function getLatest()
    {
        return $this->hasOne(Post::className(), ['id' => 'archive_of_id'])->inverseOf('archives');
    }

    /**
     * Get a list of the relationship between this log and tags
     * @return \yii\db\ActiveQuery
     */
    public function getPostTagRelations()
    {
        return $this->hasMany(PostTagRelation::className(), ['post_id' => 'id']);
    }

    /**
     * Get the list of tags associated with this log
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->via('postTagRelations');
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }


    /**
     * Find logs
     *
     * @param null $id
     * @param null $title
     * @return \common\models\Post
     * @throws NotFoundHttpException
     */
    public static function findPostByIdOrTitle($id = null, $title = null)
    {
        if ($id) {
            $post = Post::findOne($id);
        } else if ($title) {
            $post = Post::find()->andWhere(['title' => $title])->andWhere(['!=','title','about'])->orderBy('archive_of_id')->one();
        } else {
            $post = Post::find()->lastPublished()->andWhere(['!=','title','about'])->one();
        }
        if (!$post) throw new NotFoundHttpException('This log does not exist, please check the URL. It is also possible that this log has been deleted.');
        return $post;
    }
}