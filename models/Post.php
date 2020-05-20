<?php
namespace app\models;

class Post extends MyActiveRecord
{
    public static function tableName() {
        return 'posts';
    }

    public function rules()
    {
        return [
            [['title', 'body'], 'trim'],
            [['body'], 'required', 'on'=>'posts/r'],
        ];
    }

    public function scenarios()
    {
        return [
            'posts/c'=>[],
            'posts/u'=>[],
        ];
    }

    public function getFrom() {
        return $this->hasOne(User::className(), ['id' => 'from_id']);
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getTo()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('post_to', ['post_id' => 'id']);
    }

    public function getSto()
    {
        // Used in search
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('post_to', ['post_id' => 'id']);
    }

    public function getRelatedCase()
    {
        return $this->hasOne(Kase::className(), ['id' => 'rid']);
    }

    public function getRelatedTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'rid']);
    }

    public function getComments()
    {
        return $this->hasMany(Post::className(), ['n_id' => 'id']);
    }

    public function getReplies()
    {
        return $this->hasMany(Post::className(), ['n_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['n_id' => 'id']);
    }

}
