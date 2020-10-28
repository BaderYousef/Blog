<?php
namespace common\models;


use common\models\gii\TagGii;

/**
 * Class Tag
 * @package common\models
 * @property PostTagRelation[] $postTagRelations
 * @property Post[] $posts
 * @property Post[] $notArchivedPosts
 */
class Tag extends TagGii
{
    /**
     * Get all the association table information of Tag
     * @return \yii\db\ActiveQuery
     */
    public function getPostTagRelations()
    {
        return $this->hasMany(PostTagRelation::className(), ['tag_id' => 'id']);
    }

    /**
     * Get all the association table information of Tag... Posts
     * @return \yii\db\ActiveQuery|\common\models\PostQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->via('postTagRelations');
    }

    /**
     * Get tags marked not archived Posts
     * @return \yii\db\ActiveQuery|\common\models\PostQuery
     */
    public function getNotArchivedPosts()
    {
        return $this->getPosts()->notArchive();
    }

    /**
     * Get a list of tags with font size calculated based on usage
     * @return array|TagGii[]
     */
    public static function tagsWithFontSize()
    {
        $tags = static::find()
            ->innerJoinWith('postTagRelations', false)
            ->groupBy('tag.name')
            ->select('tag.name, count(tag.name) as count')
            ->asArray()
            ->all();
        if (!$tags) return [];
        $counts = array_column($tags, 'count');
        $maxCount = max($counts); // 3em
        $minCount = min($counts); // 0.5em
        array_walk($tags, function (&$tag) use ($maxCount, $minCount) {
            $tag['fontSize'] = ((($tag['count'] - $minCount) / ($maxCount - $minCount + 5)) * 2.5 + 0.5) . 'em';
        });
        return $tags;
    }
}