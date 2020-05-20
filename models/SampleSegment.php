<?php
namespace app\models;

use Yii;

class SampleSegment extends MyActiveRecord
{
    public static function tableName() {
        return 'sample_days';
    }

    public function rules()
    {
        return [
            [[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary',
                ], 'trim'],
            [[
                'language', 'title', 'note', 'tags',
                ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'sample-segments/c'=>[
                'language', 'title', 'note', 'tags',
            ],
            'sample-segments/u'=>[
                'language', 'title', 'note', 'tags',
            ],
        ];
    }

    public function getDays()
    {
        return $this->hasMany(SampleDay::className(), ['id' => 'day_id'])
            ->viaTable('sample_day_segment', ['segment_id' => 'id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
