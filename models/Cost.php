<?php
namespace app\models;

use Yii;

class Cost extends MyActiveRecord
{

    public $p_due_date, $p_due_amount;
    public $tax, $discount;
    public $df1, $tf1, $du1, $tu1,
        $df2, $tf2, $du2, $tu2,
        $df3, $tf3, $du3, $tu3,
        $df4, $tf4, $du4, $tu4,
        $df5, $tf5, $du5, $tu5;

    public static function tableName()
    {
        return 'cpt';
    }

    public function rules()
    {
        return [
            [[
                'c_type', 'r_status', 'p_status', 'p_method',
                'df1', 'tf1', 'du1', 'tu1',
                'df2', 'tf2', 'du2', 'tu2',
                'df3', 'tf3', 'du3', 'tu3',
                'df4', 'tf4', 'du4', 'tu4',
                'df5', 'tf5', 'du5', 'tu5',
                'name', 'venue_id', 'vendor_contact_id',
                'qty', 'unit', 'price', 'currency',
                'qty2', 'unit2',
                'p_due_date', 'p_due_amount',
                'tax', 'discount', 'account_ref',
                'u_for',
                'parent_cost_id',
                ], 'trim'],
            [[
                'c_type', 'r_status',
                'df1',
                'name',
                'qty', 'unit', 'price', 'currency',

                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function scenarios()
    {
        return [
            'costs/c' => [
                'c_type', 'r_status', 'p_status', 'p_method',
                'df1', 'tf1', 'du1', 'tu1',
                'df2', 'tf2', 'du2', 'tu2',
                'df3', 'tf3', 'du3', 'tu3',
                'df4', 'tf4', 'du4', 'tu4',
                'df5', 'tf5', 'du5', 'tu5',
                'name', 'venue_id', 'vendor_contact_id',
                'qty', 'unit', 'price', 'currency',
                'qty2', 'unit2',
                'p_due_date', 'p_due_amount',
                'tax', 'discount', 'account_ref',
                'u_for',
                'parent_cost_id',
                ],
            'costs/u/huan' => [
                'c_type', 'r_status', 'p_status', 'p_method',
                'df1', 'tf1', 'du1', 'tu1',
                'df2', 'tf2', 'du2', 'tu2',
                'df3', 'tf3', 'du3', 'tu3',
                'df4', 'tf4', 'du4', 'tu4',
                'df5', 'tf5', 'du5', 'tu5',
                'name', 'venue_id', 'vendor_contact_id',
                'qty', 'unit', 'price', 'currency',
                'qty2', 'unit2',
                'p_due_date', 'p_due_amount',
                'tax', 'discount', 'account_ref',
                'u_for',
                'parent_cost_id',
                ],
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

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id' => 'venue_id']);
    }

    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id' => 'tour_id']);
    }

    public function getVendor()
    {
        return $this->hasOne(Org::className(), ['id' => 'venue_id']);
    }

    public function getVendorContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'vendor_contact_id']);
    }

    public function getPaidToContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'p_to_contact_id']);
    }

    public function getMtt()
    {
        return $this->hasMany(Mtt::className(), ['cpt_id'=>'id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['rid'=>'id'])->onCondition(['rtype'=>'cpt'])->orderBy('created_at');
    }

    public function getEdits()
    {
        return $this->hasMany(CptEdit::className(), ['cpt_id'=>'id'])->orderBy('edit_dt DESC');
    }
}
