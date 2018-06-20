<?php
namespace common\models;

class TourStats extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%tour_stats}}';
	}

    public function rules()
    {
        return [
            [['countries'], 'trim'],
            //[['countries'], 'required'],
        ];
    }

    public function beforeSave($insert)
    {
        $this->countries = implode(',', $this->countries);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->countries = array_filter(explode(',', $this->countries));
        return parent::afterFind();
    }
}
