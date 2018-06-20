<?php

namespace app\models;
use common\models\File;
use Yii;

/**
 * This is the model class for table "note".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $avatar
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body', 'avatar'], 'required'],
            [['body'], 'string'],
            [['title', 'avatar'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'avatar' => Yii::t('app', 'Avatar'),
        ];
    }
    public function getFiles(){
        return $this->hasMany(File::className(), ['n_id' => 'id']);
    }
}
