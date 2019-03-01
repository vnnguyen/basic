<?php
namespace common\models;

class Term extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%terms}}';
	}

	public function attributeLabels()
	{
		return [
		];
	}

	public function rules()
	{
		return [
			[['name', 'alias', 'info'], 'filter', 'filter'=>'trim'],
			[['name', 'alias'], 'unique', 'filter'=>function($q) {
				return $q->andWhere(['taxonomy_id'=>$this->taxonomy_id]);
			}],
			[['name', 'alias', 'info', 'status', 'sorder', 'pid'], 'required'],
			[['status'], 'in', 'range'=>['on', 'off']],
			[['taxonomy_id'], 'integer'],
		];
	}

	public function getCreatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'created_by']);
	}

	public function getUpdatedBy()
	{
		return $this->hasOne(User::className(), ['id'=>'updated_by']);
	}

	public function getTaxonomy()
	{
		return $this->hasOne(Taxonomy::className(), ['id'=>'taxonomy_id']);
	}

}
