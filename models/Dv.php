<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv".
 *
 * @property string $id
 * @property string $created_dt
 * @property string $created_by
 * @property string $updated_dt
 * @property string $updated_by
 * @property string $status
 * @property string $grouping
 * @property integer $sorder
 * @property string $name
 * @property string $xday
 * @property string $search
 * @property string $search_loc
 * @property string $conds
 * @property string $note
 * @property string $data
 * @property string $is_dependent
 * @property string $venue_id
 * @property string $supplier_id
 * @property string $unit
 * @property string $whobooks
 * @property string $whopays
 * @property string $maxpax
 * @property string $stype
 * @property string $receipt
 * @property string $default_vendor
 */
class Dv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_dt', 'created_by', 'updated_dt', 'updated_by', 'status', 'grouping', 'sorder', 'name', 'xday', 'search', 'search_loc', 'conds', 'note', 'data', 'venue_id', 'supplier_id', 'unit', 'whobooks', 'whopays', 'maxpax', 'stype', 'receipt', 'default_vendor'], 'required'],
            [['created_dt', 'updated_dt'], 'safe'],
            [['created_by', 'updated_by', 'sorder', 'venue_id', 'supplier_id'], 'integer'],
            [['status', 'xday', 'note', 'data', 'is_dependent'], 'string'],
            [['grouping', 'search', 'search_loc', 'conds', 'receipt', 'default_vendor'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 128],
            [['unit', 'stype'], 'string', 'max' => 20],
            [['whobooks', 'whopays'], 'string', 'max' => 2],
            [['maxpax'], 'string', 'max' => 4],
        ];
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
            'status' => Yii::t('app', 'Status'),
            'grouping' => Yii::t('app', 'Grouping'),
            'sorder' => Yii::t('app', 'Sorder'),
            'name' => Yii::t('app', 'Name'),
            'xday' => Yii::t('app', 'Xday'),
            'search' => Yii::t('app', 'Search'),
            'search_loc' => Yii::t('app', 'Search Loc'),
            'conds' => Yii::t('app', 'Conds'),
            'note' => Yii::t('app', 'Note'),
            'data' => Yii::t('app', 'Data'),
            'is_dependent' => Yii::t('app', 'Is Dependent'),
            'venue_id' => Yii::t('app', 'Venue ID'),
            'supplier_id' => Yii::t('app', 'Supplier ID'),
            'unit' => Yii::t('app', 'Unit'),
            'whobooks' => Yii::t('app', 'Whobooks'),
            'whopays' => Yii::t('app', 'Whopays'),
            'maxpax' => Yii::t('app', 'Maxpax'),
            'stype' => Yii::t('app', 'Stype'),
            'receipt' => Yii::t('app', 'Receipt'),
            'default_vendor' => Yii::t('app', 'Default Vendor'),
        ];
    }
    public function getCp()
    {
        return $this->hasMany(Cp::className(), ['dv_id'=>'id']);
    }

    public function getDvt()
    {
        return $this->hasMany(Dvt::className(), ['dv_id'=>'id']);
    }

    public function getCpt()
    {
        return $this->hasMany(Cpt::className(), ['dv_id'=>'id']);
    }
}
