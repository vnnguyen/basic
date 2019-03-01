<?
namespace common\models;

use Yii;

class Hit extends MyActiveRecord
{
    public static function tableName()
    {
        return 'hits';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }
}
