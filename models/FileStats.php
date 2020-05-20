<?php
namespace app\models;

use Yii;

class FileStats extends MyActiveRecord
{
    public $test;

    public static function tableName()
    {
        return 'file_stats';
    }

    public function attributeLabels()
    {
    }

    public function rules()
    {
        return [
            [[
                'case_id'
                ], 'unique'],
            [[
                'contact_addr_country', 'contact_addr_region', 'contact_addr_city', 'contact_nationality',
                'group_age_0_1', 'group_age_2_11', 'group_age_12_17', 'group_age_18_25', 'group_age_26_34', 'group_age_35_50',
                'group_age_51_60', 'group_age_61_70', 'group_age_71_up', 'pax_count',
                'group_nationalities',
                'req_countries', 'req_destinations',
                'req_travel_type', 'req_is_homevisit',
                'req_tour', 'req_extensions', 'req_themes', 'budget', 'budget_currency',
                'day_count', 'start_date',
                'note',
                ], 'trim'],
            [[
                'pax_count', 'day_count',
                ], 'match', 'pattern'=>'/^\d{1,3}(-\d{1,3})?$/', 'message'=>Yii::t('x', 'Invalid')],
            [[
                'start_date',
                ], 'match', 'pattern'=>'/^[0-9]{4}(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1]))?)?$/', 'message'=>Yii::t('x', 'Invalid')],
            // [[
            //     'pax_count',
            //     ], 'required', 'message'=>'Required'],
        ];
    }

    public function scenarios()
    {
        return [
            'files/request'=>[
                'contact_addr_country', 'contact_addr_region', 'contact_addr_city', 'contact_nationality',
                'group_age_0_1', 'group_age_2_11', 'group_age_12_17', 'group_age_18_25', 'group_age_26_34', 'group_age_35_50',
                'group_age_51_60', 'group_age_61_70', 'group_age_71_up', 'pax_count',
                'group_nationalities',
                'req_countries', 'req_destinations',
                'req_travel_type', 'req_is_homevisit',
                'req_tour', 'req_extensions', 'req_themes', 'budget', 'budget_currency',
                'day_count', 'start_date',
                'note',
                ],
            'b2b/kase/c'=>['req_countries', 'pax_count', 'pax_count_ages', 'day_count', 'start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'],
            'b2b/kase/u'=>['req_countries', 'pax_count', 'pax_count_ages', 'day_count', 'start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'],
        ];
    }

    public function getFile() {
        return $this->hasOne(File::className(), ['id' => 'case_id']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function beforeSave($insert)
    {
        $dayCount = array_filter(explode('-', $this->day_count));
        if (!empty($dayCount)) {
            $this->day_count_min = $dayCount[0] ?? 0;
            $this->day_count_max = $dayCount[1] ?? $this->day_count_min;
        } else {
            $this->day_count_min = 0;
            $this->day_count_max = 0;
        }
        $paxCount = array_filter(explode('-', $this->pax_count));
        if (!empty($paxCount)) {
            $this->pax_count_min = $paxCount[0] ?? 0;
            $this->pax_count_max = $paxCount[1] ?? $this->pax_count_min;
        } else {
            $this->pax_count_min = 0;
            $this->pax_count_max = 0;
        }
        $tourStart = array_filter(explode('-', $this->start_date));
        if (!empty($tourStart)) {
            $y = $tourStart[0];
            $m = $tourStart[1] ?? 12;
            $d = $tourStart[2] ?? date('t', strtotime(implode('-', [$y, $m])));
            $this->tour_start_date = implode('-', [$y, $m, $d]);
            $this->tour_end_date = date('Y-m-d', strtotime('+'.$this->day_count_min.' days ', strtotime($this->tour_start_date)));
        }
        $this->req_is_homevisit = $this->req_is_homevisit == 1 ? 'yes' : 'no';
        $this->req_countries = isset($this->req_countries) && is_array($this->req_countries) ? implode('|', $this->req_countries) : '';
        $this->group_nationalities = isset($this->group_nationalities) && is_array($this->group_nationalities) ? implode('|', $this->group_nationalities) : '';
        $this->req_travel_type = isset($this->req_travel_type) && is_array($this->req_travel_type) ? implode('|', $this->req_travel_type) : '';
        $this->req_extensions = isset($this->req_extensions) && is_array($this->req_extensions) ? implode('|', $this->req_extensions) : '';
        $this->req_themes = isset($this->req_themes) && is_array($this->req_themes) ? implode('|', $this->req_themes) : '';
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->req_is_homevisit = $this->req_is_homevisit == 'yes' ? 1 : 0;
        $this->req_countries = array_filter(explode('|', $this->req_countries));
        $this->group_nationalities = array_filter(explode('|', $this->group_nationalities));
        $this->req_travel_type = array_filter(explode('|', $this->req_travel_type));
        $this->req_extensions = array_filter(explode('|', $this->req_extensions));
        $this->req_themes = array_filter(explode('|', $this->req_themes));
        return parent::afterFind();
    }
}
