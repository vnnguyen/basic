<?
namespace app\models;

use creocoder\nestedsets\NestedSetsBehavior;

class NestedSet extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
		return '{{%nested_sets}}';
	}

	public function behaviors() {
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
				'treeAttribute' => 'tree',
				// 'leftAttribute' => 'lft',
				// 'rightAttribute' => 'rgt',
				// 'depthAttribute' => 'depth',
			],
		];
	}

	public function transactions()
	{
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL,
		];
	}

	public static function find()
	{
		return new NestedSetQuery(get_called_class());
	}
}