<?php
namespace common\models;

class Tour extends MyActiveRecord
{

    public static function tableName() {
        return '{{%tours}}';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules()
    {
        return [];
    }

    public function scenarios()
    {
        return [
            'bookings_mw'=>[],
        ];
    }

    public function getCt()
    {
        return $this->hasOne(Ct::className(), ['id'=>'ct_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id'=>'ct_id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['tour_id'=>'id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['rid'=>'id'])->andWhere(['rtype'=>'tour']);
    }

    public function getGuides()
    {
        return $this->hasMany(User2::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_guide', ['tour_id'=>'id']);
    }

    public function getOperators()
    {
        return $this->hasMany(User2::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_user', ['tour_id'=>'id'], function($query){
            $query->where(['role'=>'operator']);
        });
    }

    public function getCskh()
    {
        return $this->hasMany(User2::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_user', ['tour_id'=>'id'], function($query){
            $query->where(['role'=>'cservice']);
        });
    }

}
