<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "".
 *
 * @property integer $id
 * @property integer $ct_id
 * @property string $version
 * @property string $content
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicesplus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'context', 'sv', 'cp', 'result', 'created_dt', 'created_by', 'updated_dt', 'updated_by'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Tour code'),
            'context' => Yii::t('app', 'Context'),
            'sv' => Yii::t('app', 'Services plus'),
            'cp' => Yii::t('app', 'Cost'),
            'result' => Yii::t('app', 'Result'),
        ];
    }
}
