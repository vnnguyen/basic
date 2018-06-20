<?
namespace common\models;

class Dvg extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dvg';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [
            [['from_dt', 'until_dt', 'name', 'search', 'via_company_id', 'price', 'currency', 'info'], 'trim'],
            [['from_dt', 'name', 'price', 'currency'], 'required'],
            [['via_company_id'], 'default', 'value'=>0],
        ];
    }

    public function scenarios()
    {
        return [
            'dvg/c'=>['from_dt', 'until_dt', 'name', 'search', 'via_company_id', 'price', 'currency', 'info'],
            'dvg/u'=>['from_dt', 'until_dt', 'name', 'search', 'via_company_id', 'price', 'currency', 'info'],
        ];
    }


    public function getDv()
    {
        return $this->hasOne(Dv::className(), ['id'=>'dv_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'via_company_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id'=>'updated_by']);
    }
}
