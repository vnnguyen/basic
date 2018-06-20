<?php

namespace app\models;
use common\models\Product;
use common\models\User2;
use Yii;

/**
 * This is the model class for table "complaint".
 *
 * @property integer $id
 * @property string $created_dt
 * @property integer $created_by
 * @property string $updated_dt
 * @property integer $updated_by
 * @property string $stype
 * @property string $severity
 * @property integer $tour_id
 * @property integer $incident_id
 * @property string $name
 * @property string $description
 * @property string $complaint_date
 * @property string $involving
 * @property integer $owner_id
 * @property string $owners
 * @property string $status
 */
class Complaint extends \yii\db\ActiveRecord
{
    public $test;
    public $tour_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complaints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'test',
                'name', 'description',
                'tour_code', 'complaint_date', 'stype',
                'complaint_user', 'incident_id',
                'status',  'owner_id', 'owners',
                ], 'trim'],
            [[
                'name', 'description',
                'tour_code', 'complaint_date', 'stype',
                'status',
                'owner_id',
                ], 'required', 'message'=>\Yii::t('app', 'Required')],
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

    public function getTour()
    {
        return $this->hasOne(Product::className(), ['id'=>'tour_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User2::className(), ['id'=>'owner_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'stype' => Yii::t('app', 'Stype'),
            'tour_id' => Yii::t('app', 'Tour ID'),
            'incident_id' => Yii::t('app', 'Incident ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'complaint_date' => Yii::t('app', 'Complaint Date'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'owners' => Yii::t('app', 'Owners'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    public function beforeSave($insert)
    {
        $this->owners = isset($this->owners) && is_array($this->owners) ? implode('|', $this->owners) : '';
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->owners = array_filter(explode('|', $this->owners));
        return parent::afterFind();
    }
}
