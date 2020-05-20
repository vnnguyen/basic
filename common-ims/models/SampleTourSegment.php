<?php
namespace common\models;

use Yii;

class SampleTourSegment extends MyActiveRecord
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
            'sample-tour-segment/c'=>[
                'language', 'title', 'note', 'tags',
            ],
            'sample-tour-segment/u'=>[
                'language', 'title', 'note', 'tags',
            ],
        ];
    }

    public function getDays()
    {
        return $this->hasMany(SampleTourDay::className(), ['id' => 'day_id'])
            ->viaTable('sample_tour_day_segment', ['segment_id' => 'id']);
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
