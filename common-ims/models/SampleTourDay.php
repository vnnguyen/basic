<?php
namespace common\models;

use Yii;

class SampleTourDay extends MyActiveRecord
{
    public static function tableName() {
        return 'sample_days';
    }

    public function rules()
    {
        return [
            [[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable'
                ], 'trim'],
            [[
                'language', 'title', 'body', 'meals',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'sample-tour-day/c'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable'
            ],
            'sample-tour-day/u'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable'
            ],
        ];
    }


    public function getPrograms()
    {
        return $this->hasMany(SampleTourProgram::className(), ['id' => 'day_id'])
            ->viaTable('sample_tour_day_segment', ['segment_id' => 'id']);
    }

    public function getSegments()
    {
        return $this->hasMany(SampleTourSegment::className(), ['id' => 'segment_id'])
            ->viaTable('sample_tour_day_segment', ['day_id' => 'id']);
    }

    public function getSiblings()
    {
        return $this->hasMany(SampleTourDay::className(), ['parent_id' => 'id'], function($q) {
            return $q->orderBy('sorder');
        });
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
