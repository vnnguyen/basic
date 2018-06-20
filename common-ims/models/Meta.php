<?php
namespace common\models;

class Meta extends MyActiveRecord
{

	public static function tableName()
    {
        return 'metas';
    }

	public function rules()
	{
		return [
		];
	}
	public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'rtype' => Yii::t('app', 'Rtype'),
            'rid' => Yii::t('app', 'Rid'),
            'format' => Yii::t('app', 'Format'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
    public function getCreatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'updated_by']);
    }

}
