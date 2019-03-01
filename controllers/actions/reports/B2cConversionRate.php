<?php
namespace app\controllers\actions\reports;

use Yii;
use yii\web\Response;

use app\models\Kase;

class B2cConversionRate extends \yii\base\Action
{
    public function run(
        $date_created = '',
        $date_created_custom = '',
        $date_assigned = '',
        $date_assigned_custom = '',
        $date_won = '',
        $date_won_custom = '',
        $date_closed = '',
        $date_closed_custom = '',

        $date_start = '',
        $date_start_custom = '',
        $date_end = '',
        $date_end_custom = '',

        $name = '',
        $status = '',
        $deal_status = '',
        $priority = '',
        $language = '',
        $owner_id = '',
        $cofr = '',
        $pv = '',

        $campaign_id = '',
        $company_id = '',

        $how_found = '', $how_contacted = '',
        $device = '', $site = '',
        $kx = '', $tx = '',
        $prospect = '',

        $source = '', $contacted = '', $found = '',

        $nationality = '',
        $age = '',
        $paxcount = '',
        array $req_countries = [],
        $req_countries_select = 'any',

        $req_start = '',
        $req_date = 'start',
        // $req_year = '',
        // $req_month = '',
        $daycount = '',
        $budget = '',
        $budget_currency = 'USD',

        $req_travel_type = '',
        $req_theme = '',
        $req_tour = '',
        $req_extension = '',

        $year = '',
        $month = '',
        $view = 'created',
        $groupby = '',
        $test = '',
        $display_table = 'date_case_created'
    )
    {
        $indexList = [
            // 'created'=>['label'=>Yii::t('x', 'Created'), 'color'=>'#00bcd4'],
            'pending'=>['label'=>Yii::t('x', 'Pending'), 'color'=>'#2196f3'],
            'won'=>['label'=>Yii::t('x', 'Won'), 'color'=>'#4caf50'],
            'lost'=>['label'=>Yii::t('x', 'Lost'), 'color'=>'#f44336'],
            'total'=>['label'=>Yii::t('x', 'Total'), 'color'=>'none'],
        ];

        for ($y = date('Y') + 10; $y >= 2007; $y--) {
            $yearList[$y] = $y;
        }
        $minYear = $maxYear = date('Y');
        // if (strpos()) {

        // }
        if (!in_array($year, $yearList)) {
            $year = date('Y');
        }

        $groupbyList = [
            'seller'=>Yii::t('x', 'Seller'),
            'source'=>Yii::t('x', 'Source'),
            // 'other'=>Yii::t('x', 'Other'),
        ];

        $sellerList = Yii::$app->db->createCommand('SELECT u.id, CONCAT_WS(" ", u.nickname, u.email) AS name, IF(u.status="on", "Active", "Inactive") AS status FROM users u, at_cases k WHERE k.owner_id=u.id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();

        $caseHowContactedList = [
            'web'=>'Web',
                'web/adwords'=>'Adwords',
                    'web/adwords/google'=>'Google Adwords',
                    'web/adwords/bing'=>'Bing Ads',
                    'web/adwords/other'=>'Other',
                'web/search'=>'Search',
                    'web/search/google'=>'Google search',
                    'web/search/bing'=>'Bing search',
                    'web/search/yahoo'=>'Yahoo! search',
                    'web/search/other'=>'Other',
                'web/link'=>'Referral',
                    'web/link/360'=>'Blog 360',
                    'web/link/facebook'=>'Facebook',
                    'web/link/other'=>'Other',
                'web/adonline'=>'Ad online',
                    'web/adonline/facebook'=>'Facebook',
                    'web/adonline/voyageforum'=>'VoyageForum',
                    'web/adonline/routard'=>'Routard',
                    'web/adonline/sitevietnam'=>'Site-Vietnam',
                    'web/adonline/other'=>'Other',
                'web/email'=>'Mailing',
                'web/direct'=>'Direct access',

            'nweb'=>'Non-web',
                'nweb/phone'=>'Phone',
                'nweb/email'=>'Email',
                    'nweb/email/tripconn'=>'TripConnexion',
                    'nweb/email/other'=>'Other',
                'nweb/walk-in'=>'Walk-in',
                'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

            'agent'=>'Via a tour company', // OLD?
        ];

        $kaseHowFoundList = [
            'returning'=>Yii::t('x', 'Returning customer'),
            'new'=>Yii::t('x', 'New customer'),
            'referred'=>Yii::t('x', 'Referred customer'),
                'referred/customer'=>Yii::t('x', 'Referred by one of Amica\'s customers'),
                'referred/amica'=>Yii::t('x', 'Referred by one of Amica\'s staff'),
                'referred/org'=>Yii::t('x', 'Referred by an organization or one of its members'), // Ca nhan, to chuc
                'referred/expat'=>Yii::t('x', 'Referred by an expat in Vietnam'),
                'referred/other'=>Yii::t('x', 'Referred from other source'),
        ];
        $sql_clause = 'created_at';
        if ($display_table == 'date_case_created') {
            $sql_clause = 'created_at';
        }
        if ($display_table == 'date_case_assigned') {
            $sql_clause = 'ao';
        }
        if ($display_table == 'date_case_won') {
            $sql_clause = 'deal_status_date';
        }
        if ($display_table == 'date_case_closed') {
            $sql_clause = 'closed';
        }
        if ($display_table == 'date_tour_start') {
            $sql_clause = 'tour_start_date';
        }
        if ($display_table == 'date_tour_end') {
            $sql_clause = 'tour_end_date';
        }
        $query = Kase::find()
            ->select(['at_cases.id', 'name', 'at_cases.status', 'ref', 'is_priority', 'deal_status', 'deal_status_date', 'opened', 'owner_id', 'at_cases.created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'tour_start_date', 'tour_end_date', 'created_at_vn'=>new \yii\db\Expression('DATE_ADD(at_cases.created_at, INTERVAL 7 HOUR)')])
            ->where(['is_b2b'=>'no'])
            ->innerJoinWith('stats');

        if (in_array($prospect, [1,2,3,4,5]) || $site != '' || $device != '') {
            $cond = [];
            if ($prospect != '') {
                $cond['prospect'] = $prospect;
            }
            if ($site != '') {
                $cond['pa_from_site'] = $site;
            }
            if ($device != '') {
                $cond['request_device'] = $device;
            }
            $query->andWhere($cond);
        }

        // if ($date1from != '' && $date1until != '') {
            // if ($view == 'created') {
            //     $dateField = 'created_at';
            // } elseif ($view == 'assigned') {
            //     $dateField = 'ao';
            // } else {
            //     $dateField = 'closed';
            // }
            // if ($view == 'created') {
            //     $query->andHaving('(created_at_vn>=:d1f AND created_at_vn<=:d1u)', [':d1f'=>$date1from.' 00:00:00', ':d1u'=>$date1until.' 23:59:59']);
            // } elseif ($view == 'won') {
            //     // TODO khi ho so co nhieu booking WON thi co the bi loi
            //     $query->select(['b.status_dt', 'at_cases.id', 'name', 'at_cases.status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'at_cases.created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'created_at_vn'=>new \yii\db\Expression('DATE_ADD(at_cases.created_at, INTERVAL 7 HOUR)')]);
            //     $query->andWhere(['deal_status'=>'won']);
            //     $query->innerJoinWith('bookings b')->onCondition(['b.status'=>'won']);
            //     $query->andWhere('status_dt>=:d1f AND status_dt<=:d1u', [':d1f'=>$date1from.' 00:00:00', ':d1u'=>$date1until.' 23:59:59']);
            // } else {
            //     $query->andWhere($dateField.'>=:d1f AND '.$dateField.'<=:d1u', [':d1f'=>$date1from, ':d1u'=>$date1until]);
            // }
        // }

        // Dates
        $len1 = strlen($date_created);
        $len1c = strlen($date_created_custom);
        if ($len1 == 4 || $len1 == 7 || $len1 == 10) {
            // yyyy OR yyyy-mm OR yyyy-mm-dd
            $query->andWhere('SUBSTRING(created_at, 1, '.$len1.')=:date1', [':date1'=>$date_created]);
        } elseif ($date_created == 'custom' && $len1c == 24 && strpos($date_created_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $date1 = explode(' -- ', $date_created_custom);
            $query->andWhere('created_at>=:date1from AND created_at<=:date1until', [':date1from'=>$date1[0].' 00:00:00', ':date1until'=>$date1[1].' 23:59:59']);
        }

        $len2 = strlen($date_assigned);
        $len2c = strlen($date_assigned_custom);
        if ($len2 == 4 || $len2 == 7 || $len2 == 10) {
            $query->andWhere('SUBSTRING(ao, 1, '.$len2.')=:date2', [':date2'=>$date_assigned]);
        } elseif ($date_assigned == 'custom' && $len2c == 24 && strpos($date_assigned_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $date2 = explode(' -- ', $date_assigned_custom);
            $query->andWhere('ao>=:date2from AND ao<=:date2until', [':date2from'=>$date2[0], ':date2until'=>$date2[1]]);
        }

        $len3 = strlen($date_won);
        $len3c = strlen($date_won_custom);
        if ($len3 == 4 || $len3 == 7 || $len3 == 10) {
            $query->andWhere(['deal_status'=>'won']);
            $query->andWhere('SUBSTRING(deal_status_date, 1, '.$len3.')=:date3', [':date3'=>$date_won]);
        } elseif ($date_won == 'custom' && $len3c == 24 && strpos($date_won_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $query->andWhere(['deal_status_date'=>'won']);
            $date3 = explode(' -- ', $date_won_custom);
            $query->andWhere('deal_status_date>=:date3from AND deal_status_date<=:date3until', [':date3from'=>$date3[0], ':date3until'=>$date3[1]]);
        }

        $len4 = strlen($date_closed);
        $len4c = strlen($date_closed_custom);
        if ($len4 == 4 || $len4 == 7 || $len4 == 10) {
            $query->andWhere('SUBSTRING(closed, 1, '.$len4.')=:date4', [':date4'=>$date_closed]);
        } elseif ($date_closed == 'custom' && $len4c == 24 && strpos($date_closed_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $date4 = explode(' -- ', $date_closed_custom);
            $query->andWhere(['status'=>'closed']);
            $query->andWhere('closed>=:date4from AND closed<=:date4until', [':date4from'=>$date4[0], ':date4until'=>$date4[1]]);
        }

        $len5 = strlen($date_start);
        $len5c = strlen($date_start_custom);
        if ($len5 == 4 || $len5 == 7) {
            $query->andWhere('SUBSTRING(tour_start_date, 1, '.$len5.')=:date5', [':date5'=>$date_start]);
        } elseif ($date_start == 'custom' && $len5c == 24 && strpos($date_start_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $date5 = explode(' -- ', $date_start_custom);
            $query->andWhere('tour_start_date>=:date5from AND tour_start_date<=:date5until', [':date5from'=>$date5[0], ':date5until'=>$date5[1]]);
        }

        $len6 = strlen($date_end);
        if ($len6 == 4 || $len6 == 7) {
            $query->andWhere('SUBSTRING(tour_end_date, 1, '.$len6.')=:date6', [':date6'=>$date_end]);
        }

        // if ($allocated != '') {
        //     $query->andWhere('ao>=:d1f AND ao<=:d1u', [':d1f'=>$date1from, ':d1u'=>$date1until]);
        // }

        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }
        if ($status != '') {
            $query->andWhere(['status'=>$status]);
        }
        if ($deal_status != '') {
            $query->andWhere(['deal_status'=>$deal_status]);
        }
        if ($priority != '') {
            $query->andWhere(['priority'=>$priority]);
        }
        if ($language != '') {
            $query->andWhere(['language'=>$language]);
        }
        if ($owner_id == 'none') {
            $query->andWhere('owner_id IS NULL');
        } elseif ($owner_id == 'all') {
            $query->andWhere('owner_id IS NOT NULL');
        } elseif ($owner_id != '') {
            if (substr($owner_id, 0, 5) == 'cofr-') {
                $query->andWhere(['cofr'=>(int)substr($owner_id, 5)]);
            } else {
                $query->andWhere(['owner_id'=>(int)$owner_id]);
            }
        }
        if ($cofr != '') {
            $query->andWhere(['cofr'=>(int)$cofr]);
        }
        if ($campaign_id == 'yes') {
            $query->andWhere('campaign_id!=0');
        } else {
            if ($campaign_id != '') {
                $query->andWhere(['campaign_id'=>$campaign_id]);
            }
        }

        if ($how_found != '') {
            $query->andWhere('LOCATE(:found, how_found)=1', [':found'=>$how_found]);
        }
        if ($how_contacted == 'unknown') {
            $query->andWhere(['how_contacted'=>'']);
        } else {
            if ($how_contacted != '') {
                if ($how_contacted == 'web-direct') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'direct']);
                } elseif ($how_contacted == 'link') {
                    $query->andWhere(['web_referral'=>'link']);
                } elseif ($how_contacted == 'social') {
                    $query->andWhere(['web_referral'=>'social']);
                } elseif ($how_contacted == 'web-search') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"');
                } elseif ($how_contacted == 'web-search-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere('SUBSTRING(web_referral, 1, 6)="search"')->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($how_contacted == 'web-adsense') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adsense']);
                } elseif ($how_contacted == 'web-bingad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/bing']);
                } elseif ($how_contacted == 'web-otherad') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/other']);
                } elseif ($how_contacted == 'web-adwords') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords']);
                } elseif ($how_contacted == 'web-adwords-amica') {
                    $query->andWhere(['how_contacted'=>'web'])->andWhere(['web_referral'=>'ad/adwords'])->andWhere(['like', 'web_keyword', 'amica']);
                } elseif ($how_contacted == 'web-trip-connexion') {
                    $query->andWhere(['web_referral'=>'ad/trip-connexion']);
                } else {
                    $query->andWhere('LOCATE(:hc, how_contacted)=1', [':hc'=>$how_contacted]);
                }
            }
        }

        if ($paxcount != '') {
            $pax = explode('-', $paxcount);
            $pax[0] = (int)$pax[0];
            if (!isset($pax[1])) {
                $pax[1] = $pax[0];
            }
            $query->andWhere(['!=', 'pax_count', '']);
            $query->andWhere('pax_count_min<=:max AND pax_count_min>=:min', [':min'=>$pax[0], ':max'=>$pax[1]]);
        }

        if ($daycount != '') {
            $day = explode('-', $daycount);
            $day[0] = (int)$day[0];
            if (!isset($day[1])) {
                $day[1] = $day[0];
            }
            $query->andWhere(['!=', 'day_count', '']);
            $query->andWhere('day_count_min<=:max AND day_count_min>=:min', [':min'=>$day[0], ':max'=>$day[1]]);
        }

        if (isset($req_countries) && is_array($req_countries) && !empty($req_countries)) {
            if ($req_countries_select == 'all' || $req_countries_select == 'only') {
                foreach ($req_countries as $dest) {
                    $query->andWhere('LOCATE("'.$dest.'", req_countries)!=0');//, [':dest'=>$dest]);
                }
                if ($req_countries_select == 'only') {
                    $query->andWhere('LENGTH(req_countries)=:len', [':len'=> 2 * count($req_countries) + count($req_countries) - 1]);//, [':dest'=>$dest]);
                }
            } elseif ($req_countries_select == 'any') {
                $orConditions = '(';
                foreach ($req_countries as $dest) {
                    if ($orConditions != '(') {
                        $orConditions .= ' OR ';
                    }
                    $orConditions .= 'LOCATE("'.$dest.'", req_countries)!=0';
                }
                $orConditions .= ')';
                $query->andWhere($orConditions);
            } else {
                // Exact
                asort($req_countries);
                $destList = implode('|', $req_countries);
                $query->andWhere(['req_countries'=>$destList]);
            }
        }

        $paxAgeGroupList = [
            '0_1'=>'<2',
            '2_11'=>'2-11',
            '12_17'=>'12-17',
            '18_25'=>'18-25',
            '26_34'=>'26-34',
            '35_50'=>'35-50',
            '51_60'=>'51-60',
            '61_70'=>'61-70',
            '71_up'=>'>70',
        ];
        if ($age != '' && in_array($age, array_keys($paxAgeGroupList))) {
            $query->andWhere('group_age_'.$age.'!=0');
        }

        if ($nationality != '  ' && strlen($nationality) == 2) {
            $query->andWhere('LOCATE(:n, group_nationalities)!=0', [':n'=>$nationality]);
        }

        if ($req_travel_type != '') {
            $query->andWhere(['req_travel_type'=>$req_travel_type]);
        }
        if ($req_theme != '') {
            $query->andWhere('LOCATE(:n, req_themes)!=0', [':n'=>$req_theme]);
        }
        if ($req_tour != '') {
            $query->andWhere('LOCATE(:n, req_tour)!=0', [':n'=>$req_tour]);
        }
        if ($req_extension != '') {
            $query->andWhere('LOCATE(:n, req_extensions)!=0', [':n'=>$req_extension]);
        }

        if ($kx == 'k0') {
            $query->andWhere(['kx'=>'']);
        } elseif ($kx == 'k17') {
            $query->andWhere('kx!="" AND kx!="k8"');
        } elseif ($kx != '') {
            $query->andWhere(['kx'=>$kx]);
        }

        // Visiting countries
        // if ($req_countries != '') {
        //     $reqCountryList = explode(',', $req_countries);
        //     foreach ($reqCountryList as $reqCountry) {
        //         $query->andWhere('LOCATE(:c, req_countries)!=0', [':c'=>$reqCountry]);
        //     }
        // }

        // $countQuery = clone $query;
        // $pagination = new Pagination([
        //     'totalCount' => $countQuery->count(),
        //     'pageSize'=>USER_ID == 1 && isset($_GET['update-kx']) ? 100 : 25,
        // ]);

        $theCases = $query
            ->with([
                'stats',
                'owner'=>function($query) {
                    return $query->select(['id', 'nickname', 'image']);
                },
                'referrer'=>function($query) {
                    return $query->select(['id', 'name', 'is_client']);
                },
                'company'=>function($query) {
                    return $query->select(['id', 'name']);
                },
                ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5000)
            ->asArray()
            ->all();

        // Get list of sellers
        $kaseSellerList = [];
        $arr_years = [];
        foreach ($theCases as $case) {
            if (!isset($case[$sql_clause])) {
                die('not exist field ('.$sql_clause.')');
            }
            $ymd = explode('-', $case[$sql_clause]);
            if (empty($ymd)) {
                die('date is empty!!!');
            }
            if (in_array($ymd[0], $arr_years)) {
                continue;
            }
            $arr_years[] = $ymd[0];
            if (!in_array($case['owner_id'], $kaseSellerList)) {
                $kaseSellerList[] = $case['owner_id'];
            }
        }
        $arr_years = array_unique($arr_years);
        $result = [];
        if (!empty($arr_years)) {
            foreach ($arr_years as $yr) {
                for ($m = 0; $m <= 12; $m ++) {
                    foreach ($indexList as $index=>$item) {
                        $result['total'][$yr][$m][$index] = 0;
                        $result['filtered'][$yr][$m][$index] = 0;
                        if ($groupby == 'source') {
                            foreach ($caseHowContactedList as $hck=>$hcn) {
                                $result['grouped-source'][$hck][$yr][$m][$index] = 0;
                            }
                        } elseif ($groupby == 'seller') {
                            foreach ($kaseSellerList as $sid) {
                                $result['grouped-seller'][$sid][$yr][$m][$index] = 0;
                            }
                        }
                    }
                }
            }
        }

        foreach ($theCases as $case) {

            // Check conditions
            $checkConditions = true;

            // Name
            // if ($checkConditions && trim($name) != '') {
            //     $thisCondition = strpos($case['name'], trim($name)) !== false;
            //     $checkConditions = $checkConditions && $thisCondition;
            // }

            $checkConditions = true; // TODO HUAN

            // Created date already assigned
            if ($view == 'created') {
                $ymd = explode('-', $case['created_at']);
                $case['m'] = (int)$ymd[1];
            } elseif ($view == 'tourstart') {
                $ymd = explode('-', $case['tour_start_date']);
                $case['m'] = (int)$ymd[1];
            } elseif ($view == 'tourend') {
                $ymd = explode('-', $case['tour_end_date']);
                $case['m'] = (int)$ymd[1];
            } else {
                $ymd = explode('-', $case['closed']);
                $case['m'] = (int)$ymd[1];
            }
            $ymd = explode('-', $case[$sql_clause]);
            if (empty($ymd)) {
                var_dump($case);die;
            }
            $case['yr'] = $ymd[0];
            $case['m'] = (int)$ymd[1];

            // Calculating
            if ($case['deal_status'] == 'won' || $case['deal_status'] == 'lost') {
                $result['total'][$case['yr']][$case['m']][$case['deal_status']] ++;
                $result['total'][$case['yr']][0][$case['deal_status']] ++;
                if ($checkConditions) {
                    $result['filtered'][$case['yr']][$case['m']][$case['deal_status']] ++;
                    $result['filtered'][$case['yr']][0][$case['deal_status']] ++;
                    if ($groupby == 'source') {
                        foreach ($caseHowContactedList as $hck=>$hcn) {
                            if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
                                $result['grouped-source'][$hck][$case['yr']][$case['m']][$case['deal_status']] ++;
                                $result['grouped-source'][$hck][$case['yr']][0][$case['deal_status']] ++;
                            }
                        }
                    } elseif ($groupby == 'seller') {
                        foreach ($kaseSellerList as $sid) {
                            if ($case['owner_id'] == $sid) {
                                $result['grouped-seller'][$sid][$case['yr']][$case['m']][$case['deal_status']] ++;
                                $result['grouped-seller'][$sid][$case['yr']][0][$case['deal_status']] ++;
                            }
                        }
                    }
                }
            } else {
                $result['total'][$case['yr']][$case['m']]['pending'] ++;
                $result['total'][$case['yr']][0]['pending'] ++;
                if ($checkConditions) {
                    $result['filtered'][$case['yr']][$case['m']]['pending'] ++;
                    $result['filtered'][$case['yr']][0]['pending'] ++;
                    if ($groupby == 'source') {
                        foreach ($caseHowContactedList as $hck=>$hcn) {
                            if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
                                $result['grouped-source'][$hck][$case['yr']][$case['m']]['pending'] ++;
                                $result['grouped-source'][$hck][$case['yr']][0]['pending'] ++;
                            }
                        }
                    } elseif ($groupby == 'seller') {
                        foreach ($kaseSellerList as $sid) {
                            if ($case['owner_id'] == $sid) {
                                $result['grouped-seller'][$sid][$case['yr']][$case['m']][$case['deal_status']] ++;
                                $result['grouped-seller'][$sid][$case['yr']][0][$case['deal_status']] ++;
                            }
                        }
                    }
                }
            }

            $result['total'][$case['yr']][$case['m']]['total'] ++;
            $result['total'][$case['yr']][0]['total'] ++;

            if ($checkConditions) {
                $result['filtered'][$case['yr']][$case['m']]['total'] ++;
                $result['filtered'][$case['yr']][0]['total'] ++;

                if ($groupby == 'source') {
                    foreach ($caseHowContactedList as $hck=>$hcn) {
                        if (substr($case['how_contacted'], 0, strlen($hck)) == $hck) {
                            $result['grouped-source'][$hck][$case['yr']][$case['m']]['total'] ++;
                            $result['grouped-source'][$hck][$case['yr']][0]['total'] ++;
                        }
                    }
                } elseif ($groupby == 'seller') {
                    foreach ($kaseSellerList as $sid) {
                        if ($case['owner_id'] == $sid) {
                            $result['grouped-seller'][$sid][$case['yr']][$case['m']]['total'] ++;
                            $result['grouped-seller'][$sid][$case['yr']][0]['total'] ++;
                        }
                    }
                }

            }
        }
        $yearList = Yii::$app->db->createCommand('SELECT YEAR(created_at) AS y FROM at_cases GROUP BY y ORDER BY y DESC')->queryAll();
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email, u.status FROM at_cases c, users u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM campaigns c ORDER BY c.start_dt DESC')->queryAll();
        $companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, at_companies c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();
        return $this->controller->render('report_b2c-conversion-rate', [
            'theCases'=>$theCases,
            'view'=>$view,
            'years'=>$arr_years,
            'month'=>$month,

            'name'=>$name,
            'status'=>$status,
            'deal_status'=>$deal_status,
            'priority'=>$priority,
            'owner_id'=>$owner_id,
            'cofr'=>$cofr,
            'pv'=>$pv,
            'language'=>$language,

            'how_found'=>$how_found,
            'how_contacted'=>$how_contacted,

            'prospect'=>$prospect,
            'device'=>$device,
            'site'=>$site,
            'kx'=>$kx,
            'tx'=>$tx,

            'ownerList'=>$ownerList,
            'campaign_id'=>$campaign_id,
            'campaignList'=>$campaignList,
            'company_id'=>$company_id,
            'source'=>$source,

            'nationality'=>$nationality,
            'age'=>$age,
            'paxcount'=>$paxcount,
            'req_countries'=>$req_countries,
            'req_countries_select'=>$req_countries_select,
            'req_date'=>$req_date,

            'daycount'=>$daycount,
            'budget'=>$budget,
            'budget_currency'=>$budget_currency,

            'req_travel_type'=>$req_travel_type,
            'req_theme'=>$req_theme,
            'req_tour'=>$req_tour,
            'req_extension'=>$req_extension,

            'yearList'=>$yearList,
            'monthList'=>$monthList,

            'date_created'=>$date_created,
            'date_created_custom'=>$date_created_custom,
            'date_assigned'=>$date_assigned,
            'date_assigned_custom'=>$date_assigned_custom,
            'date_won'=>$date_won,
            'date_won_custom'=>$date_won_custom,
            'date_closed'=>$date_closed,
            'date_closed_custom'=>$date_closed_custom,

            'date_start'=>$date_start,
            'date_start_custom'=>$date_start_custom,
            'date_end'=>$date_end,
            'date_end_custom'=>$date_end_custom,

            'yearList'=>$yearList,
            'monthList'=>$monthList,
            'result'=>$result,
            'indexList'=>$indexList,
            'groupby'=>$groupby,

            'display_table' => $display_table
        ]);
    }
}
?>