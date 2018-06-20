<?
namespace common\models;

class Cpo extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'cpo';
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
            'cpo/c'=>['from_dt', 'until_dt', 'name', 'search', 'via_company_id', 'price', 'currency', 'info'],
            'cpo/u'=>['from_dt', 'until_dt', 'name', 'search', 'via_company_id', 'price', 'currency', 'info'],
        ];
    }


    public function getDvo()
    {
        return $this->hasOne(Dvo::className(), ['id'=>'dvo_id']);
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
