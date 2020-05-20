<?php
namespace app\models;

class Message extends MyActiveRecord
{
    public static function tableName() {
        return '{{%messages}}';
    }

    public function rules()
    {
        return [
            [['title', 'body'], 'trim'],
            [['body'], 'required', 'on'=>'message/r'],
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
        return $this->hasOne(User::className(), ['id' => 'cb']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'ub']);
    }

    public function getTo()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('at_message_to', ['message_id' => 'id']);
    }

    public function getSto()
    {
        // Used in search
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('at_message_to', ['message_id' => 'id']);
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
        return $this->hasMany(Message::className(), ['n_id' => 'id']);
    }

    public function getReplies()
    {
        return $this->hasMany(Message::className(), ['n_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::className(), ['n_id' => 'id']);
    }

}
