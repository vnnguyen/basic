<?php
namespace app\models;

class Attachment extends MyActiveRecord
{

    public static function tableName()
    {
        return 'attachments';
    }

    public function rules()
    {
        return [
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id'=>'n_id']);
    }
}
