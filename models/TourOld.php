<?php
namespace app\models;

class TourOld extends MyActiveRecord
{

    public static function tableName() {
        return 'at_tours';
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
        return $this->hasMany(Cpt::className(), ['tourold_id'=>'id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['rid'=>'id'])->andWhere(['rtype'=>'tour']);
    }

    public function getParentTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'parent_tour_id']);
    }

    public function getChildTours()
    {
        return $this->hasMany(Tour::className(), ['parent_tour_id'=>'id']);
    }

    public function getGuides()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('at_tour_guide', ['tour_id'=>'id']);
    }

    public function getOperators()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tourold_id'=>'id'], function($query){
            $query->where(['role'=>['operator', 'booker']]);
        });
    }

    public function getRealOperators()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tourold_id'=>'id'], function($query){
            $query->where(['role'=>['operator']]);
        });
    }

    public function getBookers()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tourold_id'=>'id'], function($query){
            $query->where(['role'=>['booker']]);
        });
    }

    public function getTourUsers()
    {
        return $this->hasMany(TourUser::className(), ['tour_id'=>'id']);
    }

    public function getCskh()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tour_id'=>'id'], function($query){
            $query->where(['role'=>'cservice']);
        });
    }

}
