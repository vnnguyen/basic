<?php
namespace app\models;

use Yii;
use yii\base\Model;

class TourAcceptForm extends Model
{
    public $op_code, $op_name, $client_ref, $owner, $parent_tour_id, $parent_tour_relation;
    public $op_brand, $op_brand_id, $op_brand_name, $op_brand_logo;
    public $operators = [];
    public $also = [];

    public function attributeLabels()
    {
        return [
            'op_code'=>'Tour code',
            'op_name'=>'Tour name',
            'owner'=>'Tour owner',
        ];
    }

    public function rules()
    {
        return [
            [[
                'op_code', 'op_name', 'client_ref',
                'op_brand', 'op_brand_id', 'op_brand_name', 'op_brand_logo',
                'parent_tour_id', 'parent_tour_relation',
                ], 'trim'],
            [[
                'op_code', 'op_name',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

}