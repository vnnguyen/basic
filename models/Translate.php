<?php

namespace app\models;
use Yii;

class Translate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
}
