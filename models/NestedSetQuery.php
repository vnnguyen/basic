<?
namespace app\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class NestedSetQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}