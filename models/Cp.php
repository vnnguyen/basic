<?
namespace app\models;

class Cp extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'cp';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [];
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['cp_id'=>'id']);
    }

    public function getCpg()
    {
        return $this->hasMany(Cpg::className(), ['cp_id'=>'id']);
    }

    public function getVenue()
    {
        return $this->hasOne(\common\models\Venue::className(), ['id'=>'venue_id']);
    }

    public function getByCompany()
    {
        return $this->hasOne(\common\models\Company::className(), ['id'=>'by_company_id']);
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
