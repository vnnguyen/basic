<?php
namespace app\models;

use Yii;

class TourSeries extends MyActiveRecord
{

    public static function tableName() {
        return 'tour_series';
    }

    public function rules()
    {
        return [
            [[
                'b2b_client_id', 'series_name', 'series_status', 'description', 'note',
                ], 'trim'],
            [[
                'b2b_client_id', 'series_name', 'series_status',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'series_name',
                ], 'unique', 'message'=>Yii::t('x', 'Duplication found')],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'b2b_client_id']);
    }

    public function getProgram()
    {
        return $this->hasOne(Product::className(), ['id' => 'program_id']);
    }

    public function getTours()
    {
        return $this->hasMany(Product::className(), ['client_series'=>'series_name', 'client_id'=>'b2b_client_id']);
    }
}
