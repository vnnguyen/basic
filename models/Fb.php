<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fb".
 *
 * @property integer $id
 * @property integer $ct_id
 * @property string $version
 * @property string $content
 */
class Fb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ct_id', 'version', 'content'], 'required'],
            [['ct_id'], 'integer'],
            [['content'], 'string'],
            [['version'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ct_id' => Yii::t('app', 'Ct ID'),
            'version' => Yii::t('app', 'Version'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
