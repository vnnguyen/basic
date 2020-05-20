<?php
namespace app\models;

use Yii;

class SampleDay extends MyActiveRecord
{
    public static function tableName()
    {
        return 'sample_days';
    }

    public function rules()
    {
        return [
            [[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable', 'dests', 'cats',
                ], 'trim'],
            [[
                'language', 'title', 'body', 'meals',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'sample-days/c'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable', 'dests', 'cats',
            ],
            'sample-days/u'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note', 'summary', 'is_selectable', 'dests', 'cats',
            ],
        ];
    }


    public function getPrograms()
    {
        return $this->hasMany(SampleProgram::className(), ['id' => 'day_id'])
            ->viaTable('sample_day_segment', ['segment_id' => 'id']);
    }

    public function getSegments()
    {
        return $this->hasMany(SampleSegment::className(), ['id' => 'segment_id'])
            ->viaTable('sample_day_segment', ['day_id' => 'id']);
    }

    public function getSiblings()
    {
        return $this->hasMany(SampleDay::className(), ['parent_id' => 'id'], function($q) {
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

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['rid' => 'id'])->andWhere(['rtype'=>'sample-day']);
    }
}
