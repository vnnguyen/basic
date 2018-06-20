<?
namespace app\models;

class Cpg extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'cpg';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [];
    }

    public function getCp()
    {
        return $this->hasOne(Cp::className(), ['id'=>'cp_id']);
    }

    public function getViaCompany()
    {
        return $this->hasOne(\common\models\Company::className(), ['id'=>'via_company_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id'=>'updated_by']);
    }
}
