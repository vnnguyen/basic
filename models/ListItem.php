<?php
namespace app\models;

use Yii;

class ListItem extends MyActiveRecord
{
    public static function tableName() {
        return 'list_items';
    }

    public function rules()
    {
        return [
            [[
                'name', 'description', 'note'], 'trim'],
            [[
                'name'], 'required', 'message'=>Yii::t('x', 'Required')],
        ];
    }

    public function getList()
    {
        return $this->hasOne(Listt::className(), ['id'=>'list_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
