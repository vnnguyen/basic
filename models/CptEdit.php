<?
namespace common\models;

class CptEdit extends MyActiveRecord
{

    public static function tableName()
    {
        return 'cpt_history';
    }

    public function getCpt()
    {
        return $this->hasOne(Cpt::className(), ['dvtour_id'=>'latest']);
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

}
