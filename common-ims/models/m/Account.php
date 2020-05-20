<?php
namespace common\models\m;

use Yii;

class Account extends \common\models\MyActiveRecord
{
    public static function tableName() {
        return 'm_accounts';
    }

    public function rules() {
        return [
            [[
                'stype', 'name', 'currency',
                'info', 'note',
                ], 'trim'],
            [[
                'stype', 'name', 'currency',
                ], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
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
