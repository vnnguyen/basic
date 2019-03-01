<?php
namespace common\models;

class Taxonomy extends MyActiveRecord
{
	public static function tableName()
	{
		return '{{%taxonomies}}';
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
			[['name', 'alias'], 'unique'],
			[['name', 'alias', 'info', 'status', 'is_hierachical', 'is_multiple'], 'required'],
			[['status'], 'in', 'range'=>['on', 'off']],
			[['is_hierachical', 'is_multiple'], 'in', 'range'=>['yes', 'no']],
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

	public function getTerms()
	{
		return $this->hasMany(Term::className(), ['taxonomy_id'=>'id']);
	}

}
