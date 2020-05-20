<?php
namespace app\models;

use Yii;

class Product extends MyActiveRecord
{

    public static $types = [
        'tour'=>['id'=>1, 'name'=>'Private tour', 'alias'=>'tour'],
        'vpctour'=>['id'=>2, 'name'=>'VPC tour', 'alias'=>'vpctour'],
        'tcgtour'=>['id'=>3, 'name'=>'TCG tour', 'alias'=>'tcgtour'],
    ];
    
    public static function tableName() {
        return 'tours';
    }

    public function rules() {
        return [
            [[
                'title', 'about', 'client_id', 'client_series', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_from', 'price_until', 'tags', 'client_ref'
                ], 'trim'],
            [[
                'offer_type', 'language', 'title', 'pax', 'start_date', 'price_unit', 'price_for', 'price_from', 'price_until'
                ], 'required', 'message'=>Yii::t('x', 'Required')],
            [[
                'pax'
                ], 'integer', 'min'=>0],
            [[
                'price'
                ], 'number', 'min'=>0],
            [[
                'price'
                ], 'default', 'value'=>0],
            [[
                'start_date', 'price_from', 'price_until'
                ], 'date', 'format'=>'Y-m-d', 'message'=>'Date must be of "yyyy-mm-dd" format'],

            [['op_code'], 'unique'],
            [['op_code', 'op_name'], 'required'],
        ];
    }

    public function scenarios() {
        return [
            'product/c/prod'=>['title', 'about', 'language', 'pax', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
            'product/u/prod'=>['title', 'about', 'language', 'pax', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_until', 'promo', 'tags'],
            'products_c'=>['title', 'about', 'offer_type', 'language', 'pax', 'start_date', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_from', 'price_until', 'promo', 'tags'],
            'products_u'=>['title', 'about', 'offer_type', 'language', 'pax', 'start_date', 'intro', 'conditions', 'others', 'summary', 'image', 'prices', 'price', 'price_unit', 'price_for', 'price_from', 'price_until', 'promo', 'tags'],
            'b2b/program/u'=>['title', 'client_id', 'client_series', 'about', 'offer_type', 'language', 'pax', 'start_date', 'intro', 'conditions', 'others', 'summary', 'prices', 'price', 'price_unit', 'price_for', 'price_from', 'price_until', 'promo', 'tags'],
            'product/pt'=>['prices', 'price', 'price_unit', 'price_for', 'price_from', 'price_until'],
            'product/copy'=>['title', 'summary'],
            'products_u-op'=>['op_code', 'op_name'],
            'product/ref'=>['client_ref'],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }

    public function getIncidents()
    {
        return $this->hasMany(Incident::className(), ['tour_id'=>'id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id'=>'client_id']);
    }

    public function getComplaints()
    {
        return $this->hasMany(Complaint::className(), ['tour_id'=>'id']);
    }

    public function getServicesPlus()
    {
        return $this->hasMany(ServicePlus::className(), ['tour_id'=>'id']);
    }

    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['product_id'=>'id']);
    }

    public function getDays()
    {
        return $this->hasMany(Day::className(), ['rid'=>'id']);
    }

    public function getPax()
    {
        return $this->hasMany(Pax::className(), ['tour_id'=>'id']);
    }

    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['rid'=>'id'])->andWhere(['rtype'=>'product']);
    }

    public function getTournotes()
    {
        return $this->hasMany(Tournote::className(), ['tour_id'=>'id']);
    }

    public function getTourContacts()
    {
        return $this->hasMany(TourContact::className(), ['tour_id'=>'id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id'=>'contact_id'])
            ->viaTable('tour_contact', ['tour_id'=>'id']);
    }

    public function getTourStats() {
        return $this->hasOne(TourStats::className(), ['tour_id' => 'id']);
    }

    public function getStats() {
        return $this->hasOne(TourStats::className(), ['tour_id' => 'id']);
    }

    public function getTour()
    {
        return $this->hasOne(TourOld::className(), ['ct_id'=>'id']);
    }

    public function getTourOld()
    {
        return $this->hasOne(TourOld::className(), ['ct_id'=>'id']);
    }

    public function getOperators()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tour_id'=>'id'], function($q){
            $q->andWhere(['role'=>'operator']);
        });
    }

    public function getBookers()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tour_id'=>'id'], function($q){
            $q->andWhere(['role'=>'booker']);
        });
    }

    public function getCrStaff()
    {
        return $this->hasMany(User::className(), ['id'=>'user_id'])
            ->viaTable('tour_user', ['tour_id'=>'id'], function($q){
            $q->andWhere(['role'=>'cservice']);
        });
    }

    public function getGuides()
    {
        return $this->hasMany(TourGuide2::className(), ['tour_id'=>'id']);
    }

    public function getDrivers()
    {
        return $this->hasMany(TourDriver::className(), ['tour_id'=>'id']);
    }

    public function getMetas()
    {
        return $this->hasMany(Meta::className(), ['rid'=>'id'])->andWhere(['rtype'=>'product']);
    }

    public function getPresents()
    {
        return $this->hasMany(QItemTransaction::className(), ['tour_id'=>'id']);
    }

    public function getAdvances()
    {
        return $this->hasMany(TourAdvance::className(), ['tour_id'=>'id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::className(), ['tour_id'=>'id']);
    }

    public function getTourUsers()
    {
        return $this->hasMany(TourUser::className(), ['tour_id'=>'id']);
    }

    public function getDvt()
    {
        return $this->hasMany(Dvt::className(), ['tour_id'=>'id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['rid'=>'id'])->andWhere(['rtype'=>'tour']);
    }

    public function getPrints()
    {
        return $this->hasMany(TourPrint::className(), ['tour_id'=>'id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['tour_id'=>'id']);
    }

    public function getCosts()
    {
        return $this->hasMany(Cost::className(), ['tour_id'=>'id']);
    }

    public function getSurveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::className(), ['tour_id'=>'id']);
    }

    public function getCpLinks()
    {
        return $this->hasMany(CpLink::className(), ['booking_id'=>'id'])
            ->viaTable('bookings', ['product_id'=>'id']);
    }
}
