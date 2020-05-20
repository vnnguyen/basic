<?php
namespace app\models;

use Yii;

class Feedback extends MyActiveRecord
{
    public static function tableName() {
        return 'tour_feedbacks';
    }

    public $text;

    public function rules()
    {
        return [
            [[
                'stype', 'who', 'say', 'feedback', 'text', 'feedback_link',
                ], 'trim'],
            [[
                'stype', 'who', 'say', 'feedback',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'feedback_link',
                ], 'url', 'message'=>Yii::t('x', 'Invalid. Must start with https:// or http://')],
            // [[
            //     'text',
            //     ], 'required', 'message'=>Yii::t('x', 'Required'), 'when'=>function($model){
            //         return $model->what == '';
            //     }, 'whenClient' => "function (attribute, value) {
            //             return $('#feedback-what').val() == '';
            //     }"],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id'=>'rid'])->andWhere(['rtype'=>'feedback']);
    }

}
