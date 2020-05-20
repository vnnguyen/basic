<?
namespace common\models;

class Feedback extends MyActiveRecord
{
    public static function tableName() {
        return 'tour_feedbacks';
    }

    public function attributeLabels() {
        return [
        ];
    }

    public function rules()
    {
        return [
            [['price'], 'number', 'min'=>0],
        ];
    }

    public function scenarios()
    {
        return [
            'copy'=>['title', 'intro'],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User2::className(), ['id'=>'created_by']);
    }

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

}
