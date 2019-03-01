<?php
namespace app\models;

class KaseStats extends MyActiveRecord
{
    public $test;

    public static function tableName()
    {
        return '{{%case_stats}}';
    }

    public function attributeLabels()
    {
        return [
            'req_countries'=>'Travel destinations',
            'pa_pax'=>'Pax (12 or 12-15)',
            'pa_pax_ages'=>'Ages (30,32,40 or 30-40)',
            'pa_days'=>'Days (15 or 15-20)',
            'pa_start_date'=>'Start date (2015-12-25 or 2015-12 or 2015)',
            'pa_tour_type'=>'Tour type (classic, trekking, ..)',
            'pa_group_type'=>'Group type (solo, family, ..)',
            'pa_tags'=>'Tags (comma-separated)',
        ];
    }

    public function rules()
    {
        return [
            [['case_id'], 'unique'],
            [[
                'contact_addr_country', 'contact_addr_region', 'contact_addr_city', 'contact_nationality',
                'group_age_0_1', 'group_age_2_11', 'group_age_12_17', 'group_age_18_25', 'group_age_26_34', 'group_age_35_50',
                'group_age_51_60', 'group_age_61_70', 'group_age_71_up', 'group_pax_count',
                'group_nationalities',
                'req_destinations',
                'req_travel_type', 'req_is_homevisit',
                'req_web_tour', 'req_web_formula',
                'req_countries', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags',
                'note',
                ], 'trim'],
            [['pa_pax', 'pa_days'], 'match', 'pattern' => '/^\d{1,3}(-\d{1,3})?$/'],
            // [[
            //     'req_countries',
            //     'group_nationalities',
            //     ], 'required', 'message'=>'Required'],
        ];
    }

    public function scenarios()
    {
        return [
            'cases/request'=>[
                'contact_addr_country', 'contact_addr_region', 'contact_addr_city', 'contact_nationality',
                'group_age_0_1', 'group_age_2_11', 'group_age_12_17', 'group_age_18_25', 'group_age_26_34', 'group_age_35_50',
                'group_age_51_60', 'group_age_61_70', 'group_age_71_up', 'group_pax_count',
                'group_nationalities',
                'req_destinations',
                'req_travel_type', 'req_is_homevisit',
                'req_web_tour', 'req_web_formula',
                'req_countries', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags',
                'note',
                ],
            'b2b/kase/c'=>['req_countries', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'],
            'b2b/kase/u'=>['req_countries', 'pa_pax', 'pa_pax_ages', 'pa_days', 'pa_start_date', 'pa_tour_type', 'pa_group_type', 'pa_tags'],
        ];
    }

    public function getKase() {
        return $this->hasOne(Kase::className(), ['id' => 'case_id']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User2::className(), ['id' => 'updated_by']);
    }

    public function beforeSave($insert)
    {
        $this->req_is_homevisit = $this->req_is_homevisit == 1 ? 'yes' : 'no';
        $this->req_countries = isset($this->req_countries) && is_array($this->req_countries) ? implode(',', $this->req_countries) : '';
        $this->group_nationalities = isset($this->group_nationalities) && is_array($this->group_nationalities) ? implode(',', $this->group_nationalities) : '';
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->req_is_homevisit = $this->req_is_homevisit == 'yes' ? 1 : 0;
        $this->req_countries = array_filter(explode(',', $this->req_countries));
        $this->group_nationalities = array_filter(explode(',', $this->group_nationalities));
        return parent::afterFind();
    }
}
