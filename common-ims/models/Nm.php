<?
namespace common\models;

use Yii;

class Nm extends MyActiveRecord
{
    public static function tableName() {
        return '{{%ngaymau}}';
    }

    public function rules()
    {
        return [
            [[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note',
                ], 'trim'],
            [[
                'language', 'title', 'body', 'meals',
                ], 'required', 'message'=>Yii::t('app', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'nm/c'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note',
            ],
            'nm/u'=>[
                'language', 'title', 'body', 'tags', 'meals', 'transport', 'guides', 'note',
            ],
        ];
    }


    public function getProgram()
    {
        return $this->hasOne(SampleTourProgram::className(), ['id' => 'program_id']);
    }

    public function getSiblings()
    {
        return $this->hasMany(Nm::className(), ['parent_id' => 'id'], function($q) {
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
