<?
namespace common\models;

use Yii;

class Issue extends MyActiveRecord
{
    public static function tableName()
    {
        return 'issues';
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Name',
            'body'=>'Body',
        ];
    }

    public function rules()
    {
        return [
            [[
                'project_id', 'milestone', 'category', 'status', 'assigned_to', 'start_date', 'due_date', 'name', 'body'
                ], 'trim'],
            [['name'], 'required'],
        ];
    }

    public function scenarios()
    {
        return [
            'issue/c'=>[
                'project_id', 'milestone', 'category', 'status', 'assigned_to', 'start_date', 'due_date', 'name', 'body'
            ],
            'issue/u'=>[
                'project_id', 'milestone', 'category', 'status', 'assigned_to', 'start_date', 'due_date', 'name', 'body'
            ],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getAssignedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'assigned_to']);
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['rid' => 'id'])->andWhere(['rtype'=>'issue']);
    }

}
