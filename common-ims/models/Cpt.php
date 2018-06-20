<?
namespace common\models;

class Cpt extends MyActiveRecord
{

    public static function tableName()
    {
        return 'cpt';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules() {
        return [];
    }

    public function getTour()
    {
        return $this->hasOne(Tour::className(), ['id'=>'tour_id']);
    }

    public function getCp()
    {
        return $this->hasOne(Cp::className(), ['id'=>'cp_id']);
    }

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id'=>'venue_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id'=>'by_company_id']);
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

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['rid'=>'dvtour_id'])->onCondition(['rtype'=>'cpt'])->orderBy('created_at');
    }

    public function getEdits()
    {
        return $this->hasMany(CptEdit::className(), ['latest'=>'dvtour_id'])->orderBy('created_at DESC');
    }

    public function getMtt()
    {
        return $this->hasMany(Mtt::className(), ['cpt_id'=>'dvtour_id']);
    }

}
