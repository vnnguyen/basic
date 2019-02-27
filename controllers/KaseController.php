<?php

namespace app\controllers;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\validators\EmailValidator;
use yii\helpers\ArrayHelper;

use common\models\Kase;
use common\models\KaseStats;
use common\models\CpLink;
use common\models\Country;
use common\models\Person;
use common\models\Inquiry;
use common\models\Message;
use common\models\Note;
use common\models\Sysnote;
use common\models\Mail;
use common\models\User2;
use common\models\Company;
use common\models\Campaign;
use common\models\UsersUuForm;
use common\models\Meta;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KaseController extends MyController
{
    public function actionB2bc($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        if (!in_array(USER_ID, [1, 4432, 26435, 11724,])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theCase->is_b2b = $theCase->is_b2b == 'yes' ? 'no' : 'yes';
        $theCase->save(false);
        return $this->redirect('/cases/r/'.$theCase['id']);
    }

    // 160723 Acheteur / non-acheteur report
    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionAna($id = 0)
    {
        $theCase = Kase::find()
            ->with([
                'owner'=>function($q) {
                    return $q->select(['id', 'email']);
                }
                ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        $theSysnote = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$theCase['id'], 'action'=>'kase/ana'])
            ->asArray()
            ->one();

        if (!Yii::$app->request->isPost) {
            if ($theSysnote) {
                $pos = strpos($theSysnote['info'], '[#QA]');
                $ana = substr($theSysnote['info'], 0, $pos);
                $str = substr($theSysnote['info'], $pos);
                $email = $theCase['owner']['email'];
            } else {
                $ana = $theCase['deal_status'] == 'won' ? 'a_160730' : 'na_160730';
                $str = '';
                $email = $theCase['owner']['email'];
            }
        } else {
            $ana = Yii::$app->request->post('ana');
            $str = Html::encode(Yii::$app->request->post('str'));
            $email = Yii::$app->request->post('email');
        }

        if (Yii::$app->request->isPost && isset($_POST['str']) && $_POST['str'] != '' && isset($_POST['ok']) && $_POST['ok'] == 'ok') {
            if ($theSysnote) {
                Yii::$app->db->createCommand()
                    ->update('at_sysnotes', [
                        'created_at'=>NOW,
                        'ip'=>USER_IP,
                        'info'=>$ana.'[#QA]'.$str,
                        ],[
                        'id'=>$theSysnote['id'],
                        ]
                    )
                    ->execute();
            } else {
                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/ana',
                        'rtype'=>'case',
                        'rid'=>$theCase['id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$ana.'[#QA]'.$str,
                    ])
                    ->execute();
            }
            // Email people
            // TODO check email list
            $emailList = explode(',', str_replace(' ', '', $email));
            if (!empty($emailList)) {
                $args = [
                    ['from', Yii::$app->user->identity->email, Yii::$app->user->identity->nickname.' on IMS'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];

                foreach ($emailList as $emailAddress) {
                    $args[] = ['to', $emailAddress];
                }

                $this->mgIt(
                    'Exit survey / Kết quả thăm dò ý kiến sau bán hàng - '.$theCase['name'],
                    '//mg/kase_ana',
                    [
                        'theCase'=>$theCase,
                        'anaString'=>$ana.'[#QA]'.$str,
                    ],
                    $args
                );
            }
            return $this->redirect('/cases/r/'.$theCase['id']);
        }

        return $this->render('kase_ana', [
            'theCase'=>$theCase,
            'ana'=>$ana,
            'str'=>$str,
            'email'=>$email,
        ]);
    }

    // Update
    public function actionThy()
    {
        // 150806
        $sql = 'select substring(i.form_name, 1, 3) as site, k.id, k.name from at_cases k, at_inquiries i WHERE i.case_id=k.id AND i.id >7533';
        $inquiries = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($inquiries as $inquiry) {
            echo '<br>', $inquiry['site'], ' - ', $inquiry['name'];
            $site = trim($inquiry['site'], '_');
            if (in_array($site, ['fr', 'val', 'vac', 'en', 'ami', 'vpc'])) {
                Yii::$app->db->createCommand()->update('at_case_stats', ['pa_from_site'=>$site], ['case_id'=>$inquiry['id']])->execute();
                echo ' / OK';
            }
        }
    }

    public function actionIndex(
        $created = '',
        $allocated = '',
        $won = '',
        $closed = '',

        $view = 'created',
        // $year = '',
        // $month = '',
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
        $is_priority = '',
        $language = '',
        $owner_id = '',
        $cofr = '',
        $pv = '',

        $campaign_id = '',
        $company_id = '',

        $how_found = '', $how_contacted = '',
        $device = '', $site = '',
        $kx = '', $tx = '', $kxcost = '',
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
        array $export_column = [],
        $downloadToken = '',
        $test = ''
        )
    {
        session_write_close();
        ignore_user_abort(true);
        $query = Kase::find()
            ->select(['at_cases.id', 'name', 'kx_cost', 'at_cases.status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'at_cases.created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'created_at_vn'=>new \yii\db\Expression('DATE_ADD(at_cases.created_at, INTERVAL 7 HOUR)')])
            ->where(['is_b2b'=>'no'])
            ->innerJoinWith('stats');

        if (!in_array($view, ['created', 'assigned', 'closed', 'won'])) {
            $view = 'created';
        }

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
        $len6c = strlen($date_end_custom);
        if ($len6 == 4 || $len6 == 7) {
            $query->andWhere('SUBSTRING(tour_end_date, 1, '.$len6.')=:date6', [':date6'=>$date_end]);
        } elseif ($date_end == 'custom' && $len6c == 24 && strpos($date_end_custom, ' -- ') !== false) {
            // yyyy-mm-dd -- yyyy-mm-dd
            $date6 = explode(' -- ', $date_end_custom);
            $query->andWhere('tour_end_date>=:date6from AND tour_end_date<=:date6until', [':date6from'=>$date6[0], ':date6until'=>$date6[1]]);
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
        if ($is_priority == 'yes') {
            $query->andWhere(['is_priority'=>['yes',1,2,3,4]]);
        } elseif ($is_priority != '') {
            $query->andWhere(['is_priority'=>$is_priority]);
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

        // Customer type
        // if ($tx != '') {
        //     if ($tx == 't2') {
        //         // Returning
        //         $query->andWhere(['how_found'=>'returning/customer']);
        //     } elseif ($tx == 't3') {
        //         // New and referred
        //         $query->andWhere('SUBSTRING(how_found, 1, 8)="referred"');
        //     } else {
        //         // New and not referred
        //         $query->andWhere('how_found!="returning/customer" AND SUBSTRING(how_found, 1, 8)!="referred"');
        //     }
        // }

        if ($how_found == 't0') {
            $query->andWhere('how_found=""');
        } elseif ($how_found != '') {
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


        // if ($date2from != '' && $date2until != '') {
        //     if ($req_date == 'start') {
        //         if ($date2from == $date2until) {
        //             $query->andWhere('tour_start_date=:d2f', [':d2f'=>$date2from]);
        //         } else {
        //             $query->andWhere('tour_start_date>=:d2f AND tour_start_date<=:d2u', [':d2f'=>$date2from, ':d2u'=>$date2until]);
        //         }
        //     } else {
        //         if ($date2from == $date2until) {
        //             $query->andWhere('tour_end_date=:d2f', [':d2f'=>$date2from]);
        //         } else {
        //             $query->andWhere('tour_end_date>=:d2f AND tour_end_date<=:d2u', [':d2f'=>$date2from, ':d2u'=>$date2until]);
        //         }
        //     }
        // }

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

        if ($kxcost == 'yes') {
            $query->andWhere('kx_cost IS NOT NULL');
        } elseif ($kxcost == 'no') {
            $query->andWhere('kx_cost IS NULL');
        }

        // Visiting countries
        // if ($req_countries != '') {
        //     $reqCountryList = explode(',', $req_countries);
        //     foreach ($reqCountryList as $reqCountry) {
        //         $query->andWhere('LOCATE(:c, req_countries)!=0', [':c'=>$reqCountry]);
        //     }
        // }

        // 170918 Ngo Hang muon xem nhung HS chua duoc edit request
        if (isset($_GET['editrequest'])) {
            $year = $_GET['year'] ?? 2017;
            if (!in_array($year, [2016, 2017, 2018])) {
                $year = 2018;
            }
            $query = Kase::find()
                ->innerJoinWith('stats')
                ->where(['is_b2b'=>'no', 'deal_status'=>['lost', 'pending'], 'req_countries'=>''])
                ->andWhere('YEAR(created_at)=:year', [':year'=>$year]);
        }

        // export to excel

        $export_columns = [
            'created_at' => Yii::t('x', 'Created date'),
            'ao' => Yii::t('x', 'Assign date'),
            'closed' => Yii::t('x', 'Closed date'),
            'name' => Yii::t('x', 'Case name'),
            'owner-nickname' => Yii::t('x', 'Owner'),
            'stats-kx' => Yii::t('x', 'Source kx'),
            'how_found' => Yii::t('x', 'Source tx'),
            'stats-req_countries' => Yii::t('x', 'Destinations'),
            'stats-day_count' => Yii::t('x', 'Days'),
            'stats-pax_count' => Yii::t('x', 'Pax'),
            'closed_note' => Yii::t('x', 'Note'),
        ];
        if (isset($export_column) && count($export_column) > 0 && $downloadToken != '') {
            $arr_columns = array_diff($export_column, ['']);
            if (!empty($arr_columns)) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                foreach ($arr_columns as $key => $col_name) {
                    $columnIndex = $key + 1;
                    $spreadsheet->getActiveSheet()->setCellValue($this->stringFromColumnIndex($columnIndex).'1', (isset($export_columns[$col_name])) ? $export_columns[$col_name] : 'unknown['. $col_name .']');
                }
                $rows_cols = [];
                $countQuery = clone $query;
                $limit = $countQuery->count() < 1000 ?$countQuery->count(): 1000;
                $pages = ceil( $countQuery->count() / $limit );

                $k = 3;
                for ( $page = 0 ; $page < $pages ; $page++ ) {
                    $offset = $page * $limit;
                    $theCases = $query
                        ->orderBy('at_cases.created_at DESC')
                        ->offset( $offset )
                        ->limit( $limit )
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
                        ->asArray()
                        ->all();
                    foreach ($theCases as $case) {
                        $row_data = [];
                        foreach ($arr_columns as $col_name) {
                            if (strpos($col_name, '-') !== false) {
                                $cols = explode('-', $col_name);
                                $arr_cols = [];
                                foreach ($cols as $col) {
                                    if (isset($case[$col]) && $case[$col] != null) {
                                        $arr_cols = $case[$col];
                                    } elseif (isset($arr_cols[$col])) {
                                        $arr_cols = $arr_cols[$col];
                                    } else {
                                        $arr_cols = null;
                                    }
                                }
                                $row_data[] = $arr_cols;
                            } elseif (isset($case[$col_name])) {
                                $row_data[] = $case[$col_name];
                            } else {
                                var_dump($arr_columns);die('not found index '. $col_name);
                            }
                        }
                        $rows_cols[] = $row_data;
                    }
                    $spreadsheet->getActiveSheet()->fromArray($rows_cols, null, 'A'.$k);
                    $k += count($rows_cols) + 1;
                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='. rand(1, 100) . 'report.Xlsx');

                sleep(6);

                // Cookie will be expire after 20 seconds...
                // $cookies = Yii::$app->response->cookies;
                // $cookies->add(new \yii\web\Cookie([
                //     'name' => 'fileDownloadToken',
                //     'value' => $downloadToken,
                //     'expire' => time() + 20
                // ]));
                setcookie("downloadToken", $downloadToken, time() + 20);

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;

            }
        }
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>USER_ID == 1 && isset($_GET['update-kx']) ? 100 : 25,
        ]);

        $theCases = $query
            // ->select(['id', 'name', 'status', 'ref', 'is_priority', 'deal_status', 'opened', 'owner_id', 'created_at', 'ao', 'how_found', 'web_referral', 'web_keyword', 'campaign_id', 'how_contacted', 'owner_id', 'company_id', 'info', 'closed', 'closed_note', 'created_at_vn'=>new \yii\db\Expression('DATE_FORMAT(DATE_ADD(created_at, INTERVAL 7 HOUR), "%Y-%m-%d")')])
            ->orderBy('at_cases.created_at DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
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
            ->asArray()
            ->all();

        // List of months
        $yearList = Yii::$app->db->createCommand('SELECT YEAR(created_at) AS y FROM at_cases GROUP BY y ORDER BY y DESC')->queryAll();
        for ($m = 1; $m <= 12; $m ++) {
            $monthList[$m] = $m;
        }
        $ownerList = Yii::$app->db->createCommand('SELECT u.id, u.lname, u.email, u.status FROM at_cases c, users u WHERE u.id=c.owner_id GROUP BY u.id ORDER BY u.lname, u.fname')->queryAll();
        $campaignList = Yii::$app->db->createCommand('SELECT c.id, c.name, c.start_dt FROM campaigns c ORDER BY c.start_dt DESC')->queryAll();
        $companyList = Yii::$app->db->createCommand('SELECT c.id, c.name FROM at_cases k, clients c WHERE k.company_id=c.id GROUP BY k.company_id ORDER BY c.name')->queryAll();



        return $this->render('kase_index', [
            'created'=>$created,
            'allocated'=>$allocated,
            'won'=>$won,
            'closed'=>$closed,

            'pagination'=>$pagination,
            'theCases'=>$theCases,
            'view'=>$view,

            'view'=>$view,

            'name'=>$name,
            'status'=>$status,
            'deal_status'=>$deal_status,
            'is_priority'=>$is_priority,
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
            'kxcost'=>$kxcost,
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
            'export_column' => $export_column,
            'export_columns' => $export_columns

        ]);
    }
    public static function stringFromColumnIndex($columnIndex)
    {
        static $indexCache = [];
        if (!isset($indexCache[$columnIndex])) {
            $indexValue = $columnIndex;
            $base26 = null;
            do {
                $characterValue = ($indexValue % 26) ?: 26;
                $indexValue = ($indexValue - $characterValue) / 26;
                $base26 = chr($characterValue + 64) . ($base26 ?: '');
            } while ($indexValue > 0);
            $indexCache[$columnIndex] = $base26;
        }
        return $indexCache[$columnIndex];
    }


    // Testing: send CPL
    public function actionSendCpl($id = 0, $action = 'send', $cplink_id = 0)
    {
        // actions = [send, resend, confirm, reset] reset = change uid
        $theCase = Kase::find()
            ->with([
                'owner',
                'people',
                'bookings',
                'bookings.product'
            ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }
        if (empty($theCase['bookings'])) {
            throw new HttpException(404, 'No tour itineraries found.');
        }
        if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if (!in_array($theCase['language'], ['en', 'fr', 'vi'])) {
            throw new HttpException(403, 'Language not supported.');
        }

        // Already sent links
        $sentLinks = CpLink::find()
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $subject = 'Vui lòng cung cấp thông tin cho chuyến du lịch sắp tới';
        $message = <<<'TXT'
<p>Cảm ơn quý khách đã tin tưởng vào dịch vụ của Amica Travel.</p>
<p>Bước tiếp theo, xin quý khách dành chút thời gian để cung cấp một số thông tin cần thiết cho chuyến du lịch.</p>
<p>Xin sử dụng đường link dưới đây để đến Trang Khách hàng dành riêng cho quý khách, làm theo hướng dẫn trên trang và điền các thông tin.</p>
<p>Chú ý: Quý khách vui lòng điền thông tin cho <strong>mọi thành viên</strong> trong nhóm đi tour của mình. Nếu trong đoàn tour có những người mà quý khách không biết thông tin, xin cho tôi biết, tôi sẽ gửi link riêng cho những người ấy.</p>
<p>{{ $link }}</p>
<p>Quý khách không nên chia sẻ đường link này với người khác vì mỗi người đều có đường link riêng của mình.</p>
<p>Nếu có ý kiến hay câu hỏi, xin vui lòng liên hệ với tôi theo địa chỉ email: {{ $email }}.</p>
<p>Trân trọng,</p>
<p>{{ $name }}<br>
Nhân viên tư vấn tour<br>
AMICA TRAVEL</p>
TXT;

        if ($theCase['language'] == 'en') {
            $subject = 'Your registration for the next trip with Amica Travel';
            $message = <<<'TXT'
<p>Dear Sir/Madam,</p>
<p>Nous sommes ravies que notre proposition vous convienne et nous vous remercions de votre confiance.</p>
<p>Afin de lancer la procédure de réservation officielle, nous vous remercions de bien vouloir nous fournir les renseignements nécessaires pour votre prochain voyage.</p>
<p>Nous vous invitons à cliquer sur le lien ci-dessous afin d’accéder à votre propre page client. Suivez ensuite les instructions et entrez les informations demandées.</p>
<p>This is your link: {{ $link }}</p>

<p>PLEASE NOTE: Veuillez compléter les informations pour tous les participants de votre groupe sur notre formulaire en ligne. Si vous ne connaissez pas les informations d’une ou de plusieurs personnes, merci de nous en informer, nous enverrons un lien différent directement à la personne concernée.</p>
<p>Si vous partagez le lien avec les autres participants inscrits sur la liste, merci de leur dire de faire attention à ne pas modifier les informations concernant les autres membres du groupe.</p>

<p>Nous vous rappelons à ce stade, et tel que mentionné dans notre offre, qu’en cas d’indisponibilité d’une ou de plusieurs prestations (hébergement, vol, train…) prévues dans le programme, nous vous en informerons tout en vous proposant des solutions de remplacement équivalentes.</p>
<p>Should you have any questions or comments, do not hesitate to contact me {{ $email }}.</p>

<p>Yours sincerely,</p>

<p>{{ $name }}<br>
Your travel consultant<br>
AMICA TRAVEL
TXT;
        } elseif ($theCase['language'] == 'fr') {
            $subject = '[TEST] Fiche de réservation - renseignements nécessaires à fournir pour votre prochain voyage';
            $message = <<<'TXT'
<p>Bonjour Madame/Monsieur,</p>
<p>Nous sommes ravies que notre proposition vous convienne et nous vous remercions de votre confiance.</p>
<p>Afin de lancer la procédure de réservation officielle, nous vous remercions de bien vouloir nous fournir les renseignements nécessaires pour votre prochain voyage.</p>
<p>Nous vous invitons à cliquer sur le lien ci-dessous afin d’accéder à votre propre page client. Suivez ensuite les instructions et entrez les informations demandées.</p>
<p>Voici votre lien : {{ $link }}</p>

<p>NOTE : Veuillez compléter les informations pour tous les participants de votre groupe sur notre formulaire en ligne. Si vous ne connaissez pas les informations d’une ou de plusieurs personnes, merci de nous en informer, nous enverrons un lien différent directement à la personne concernée.</p>
<p>Si vous partagez le lien avec les autres participants inscrits sur la liste, merci de leur dire de faire attention à ne pas modifier les informations concernant les autres membres du groupe.</p>

<p>Nous vous rappelons à ce stade, et tel que mentionné dans notre offre, qu’en cas d’indisponibilité d’une ou de plusieurs prestations (hébergement, vol, train…) prévues dans le programme, nous vous en informerons tout en vous proposant des solutions de remplacement équivalentes.</p>
<p>Si vous avez des questions, n’hésitez pas à nous contacter.</p>

<p>Amicalement,</p>

<p>{{ $name }}<br>
Votre conseillère en voyage<br>
AMICA TRAVEL
TXT;
        }

        $attachmentList = [
            'en'=>[],
            'fr'=>[
                1=>'Amica Travel - 2016 - Modalités de paiement d\'Amica Travel - 5.9.2016.pdf',
                2=>'Amica Travel - 2016 - Conditions générales de vente.pdf',
                3=>'Amica Travel - 2016 - Démarches à suivre.pdf',
            ],
            'vi'=>[],
        ];

        if ($action == 'resend' && $cplink_id != 0) {
            $theForm = CpLink::findOne($cplink_id);
            $theForm['attachments'] = array_keys($attachmentList[$theForm['language']]);
            $uid = $theForm['uid'];
        } elseif ($action == 'send') {
            $theForm = new CpLink;
            $theForm['message'] = $message;
            $theForm['attachments'] = array_keys($attachmentList[$theCase['language']]);
            $uid = Yii::$app->security->generateRandomString(10);
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            foreach ($theCase['people'] as $user) {
                if ($user['email'] == $theForm['customer_email']) {
                    $thePax = [
                        'id'=>$user['id'],
                        'email'=>$user['email'],
                        'name'=>$user['name'],
                    ];
                }
            }

            $theForm->created_dt = NOW;
            $theForm->created_by = USER_ID;
            $theForm->status = 'sent';
            $theForm->case_id = $theCase['id'];
            $theForm->customer_id = $thePax['id']; // TODO remove
            $theForm->customer_name = $thePax['name']; // TODO remove
            $theForm->customer_email = $thePax['email']; // TODO remove
            $theForm->uid = $uid;
            $theForm->language = $theCase['language'];

            $theForm->save(false);

            $theLink = 'https://client.amica-travel.com/s/'.$theForm['id'].'.'.$uid;

            $message = str_replace(['{{ $email }}', '{{ $name }}', '{{ $link }}'], [$theCase['owner']['email'], $theCase['owner']['fname'].' '.$theCase['owner']['lname'], Html::a($theLink, $theLink)], $theForm['message']);
            $args = [
                ['from', $theCase['owner']['email'], $theCase['owner']['fname'].' '.$theCase['owner']['lname'], 'Amica Travel'],
                // ['to', $thePax['customer_email'], $thePax['customer_name']],
                // ['to', 'ngo.hang@amica-travel.com', 'Hằng Ngô Amica Travel'],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];

            if (!empty($theForm->attachments)) {
                foreach ($theForm->attachments as $attachment) {
                    $args[] = ['attachment', 'www/special/'.$attachmentList[$theCase['language']][$attachment]];
                }
            }

            // \fCore::expose($theForm);
            // \fCore::expose($args);
            // exit;

            $this->mgIt(
                $subject,
                '//mg/cases_send-cpl',
                [
                    'theCase'=>$theCase,
                    'thePax'=>$thePax,
                    'message'=>$message,
                ],
                $args
            );

            // Sys note
            // Yii::$app->db->createCommand()->insert('at_sysnotes', [
            //     'created_at'=>NOW,
            //     'user_id'=>USER_ID,
            //     'action'=>'kase/send-cpl',
            //     'rtype'=>'case',
            //     'rid'=>$theCase['id'],
            //     'uri'=>'',
            //     'ip'=>'',
            //     'info'=>$theLink,
            //     ])->execute();

            Yii::$app->session->setFlash('info', 'The link is '.Html::a($theLink, $theLink, ['class'=>'alert-link', 'rel'=>'external']));
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('cases_send-cpl', [
            'theCase'=>$theCase,
            'theForm'=>$theForm,
            'attachmentList'=>$attachmentList,
            'sentLinks'=>$sentLinks,
        ]);
    }

    // Extra report info, B2C only
    public function actionStats($month = 0)
    {
        // Huan, PA, Fleur, Doan Ha
        if (!in_array(USER_ID, [1, 2, 695, 4432, 26435, 35887])) {
            throw new HttpException(403, 'Access denied.');
        }

        // Ajax update
        if (Yii::$app->request->isAjax) {
            if (isset($_POST['pk'], $_POST['name'], $_POST['value'])) {
                // Prospect
                if ($_POST['name'] == 'prospect' && in_array(USER_ID, [4432, 26435, 35887])) {
                    $sql = 'INSERT INTO at_case_stats (case_id, prospect, prospect_updated_dt, prospect_updated_by) VALUES (:case_id, :prospect, :updated_dt, :updated_by) ON DUPLICATE KEY UPDATE prospect=:prospect, prospect_updated_dt=:updated_dt, prospect_updated_by=:updated_by';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':prospect'=>$_POST['value'],
                        ':updated_dt'=>NOW,
                        ':updated_by'=>USER_ID,
                    ])->execute();
                    return true;
                }
                // Diem den
                if ($_POST['name'] == 'destinations') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_destinations) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_destinations=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // So pax
                if ($_POST['name'] == 'pax') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_pax) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_pax=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Do tuoi
                if ($_POST['name'] == 'pax_ages') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_pax_ages) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_pax_ages=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // So ngay
                if ($_POST['name'] == 'days') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_days) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_days=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Khoi hanh
                if ($_POST['name'] == 'start_date') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_start_date) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_start_date=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Kieu tour
                if ($_POST['name'] == 'tour_type') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_tour_type) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_tour_type=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Kieu di
                if ($_POST['name'] == 'group_type') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_group_type) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_group_type=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
                // Tags
                if ($_POST['name'] == 'tags') {
                    $sql = 'INSERT INTO at_case_stats (case_id, pa_tags) VALUES (:case_id, :value) ON DUPLICATE KEY UPDATE pa_tags=:value';
                    Yii::$app->db->createCommand($sql, [
                        ':case_id'=>$_POST['pk'],
                        ':value'=>$_POST['value'],
                    ])->execute();
                    return true;
                }
            }
            throw new HttpException(403, 'Access denied');
        }

        $sql = 'SELECT SUBSTRING(created_at,1,7) AS ym FROM at_cases GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();

        if (strlen($month) != 7) {
            $month = date('Y-m');
        }
        $query = Kase::find()
            ->where('is_b2b="no"')
            ->andWhere('SUBSTRING(created_at,1,7)=:month', [':month'=>$month]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theCases = $query
            ->select(['id', 'name', 'owner_id', 'created_at', 'how_found', 'how_contacted', 'web_referral', 'ref', 'status', 'deal_status', 'is_priority'])
            ->with([
                'owner'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'stats',
            ])
            ->orderBy('created_at DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('cases_stats', [
            'pagination'=>$pagination,
            'theCases'=>$theCases,
            'month'=>$month,
            'monthList'=>$monthList,
        ]);
    }

    // My open cases
    public function actionOpen()
    {
        $theCases = Kase::find()
            ->where(['and', ['status'=>['open']], ['or', 'owner_id='.USER_ID, 'cofr='.USER_ID]])
            //->where(['and', ['status'=>['open', 'onhold']], ['or', 'owner_id=1677', 'cofr=1677']])
            ->orderBy('ao DESC, name')
            ->with(['tasks'=>function($q) {
                return $q->andWhere(['status'=>'on']);
                }
            ])
            ->asArray()
            ->limit(1000)
            ->all();

        return $this->render('cases_open', [
            'theCases'=>$theCases,
        ]);
    }

    // Edit customer request
    public function actionRequest($id = 0)
    {
        if (USER_ID != 1) {
            // throw new HttpException(403, 'Page is being updated. Please come back later.');
        }

        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with(['stats', 'inquiries'])
            ->one();

        if (!in_array(USER_ID, [34718, 1, 695, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'open') {
            throw new HttpException(403, 'Case is not open.');
        }

        if ($theCase['is_b2b'] == 'yes') {
            return $this->redirect('/b2b/cases/u/'.$theCase['id']);
        }

        if (!$theCase['stats']) {
            Yii::$app->db->createCommand()
                ->insert('at_case_stats', [
                    'case_id'=>$theCase['id'],
                    'updated_at'=>NOW,
                    'updated_by'=>USER_ID,
                ])->execute();
            return $this->redirect('@web/cases/request/'.$theCase['id']);
        }

        $caseStats = $theCase['stats'];
        $caseStats->scenario = 'cases/request';
        if ($caseStats->req_travel_type != '') {
            $caseStats->req_travel_type = explode(',', $caseStats->req_travel_type);
        }
        // if ($caseStats->req_web_formula != '') {
        //     $caseStats->req_web_formula = explode(',', $caseStats->req_web_formula);
        // }


        /*//=============>>>>>>>>>>>>>>> Anh Huân thay thế Chủ để Tuor bằng một trường nào đó $caseStats->theme_matic
         if ($caseStats->note != '') {
             $caseStats->note = explode(',', $caseStats->note);
         }
        */
        if (count($theCase['inquiries']) > 0) {
            $inquiryData = @unserialize($theCase['inquiries'][0]['data']);
            if ($caseStats->contact_addr_country == '') {
                $caseStats->contact_addr_country = isset($inquiryData['country']) ? strtolower($inquiryData['country']) : '';
            }
            if ($caseStats->contact_addr_region == '') {
                $caseStats->contact_addr_region = isset($inquiryData['region']) ? $inquiryData['region'] : '';
            }
            if ($caseStats->contact_addr_city == '') {
                $caseStats->contact_addr_city = isset($inquiryData['ville']) ? $inquiryData['ville'] : '';
            }
            if ($caseStats->contact_nationality == '') {
                $caseStats->contact_nationality = isset($inquiryData['country']) ? strtolower($inquiryData['country']) : '';
            }
            //=============>>>>>>>>>>>>>>> Anh Huân thay thế Chủ để Tuor bằng một trường nào đó $caseStats->theme_matic
             // if ($caseStats->note == '') {
             //     $caseStats->note = isset($inquiryData['tourThemes']) ? $inquiryData['tourThemes'] : '';
             // }
            if (count($caseStats->req_countries) == 0) {
                $arr_des = [];

                if (count($inquiryData['countriesToVisit']) > 0) {
                    foreach ($inquiryData['countriesToVisit'] as $k => $des) {
                        switch ($des) {
                            case 'Vietnam':
                                $arr_des[] = 'vn';
                                break;
                            case 'Laos':
                                $arr_des[] = 'la';
                                break;
                            case 'Cambodge':
                                $arr_des[] = 'kh';
                                break;
                            case 'Birmanie':
                                $arr_des = 'mm';
                                break;
                        }
                    }
                    $caseStats->req_countries = $arr_des;
                }
            }
            if ($caseStats->pa_start_date == '') {
                if ($inquiryData['departureDate'] != '') {
                    $arr = explode('-', $inquiryData['departureDate']);
                    krsort($arr);
                }
                $caseStats->pa_start_date = implode('-', $arr);
            }
            // if ($caseStats->group_pax_count == '') {
            //     $numberOfTravelers12 = isset($inquiryData['numberOfTravelers12']) ? $inquiryData['numberOfTravelers12'] : 0;
            //     $numberOfTravelers2 = isset($inquiryData['numberOfTravelers12']) ? $inquiryData['numberOfTravelers12'] : 0;
            //     $numberOfTravelers0 = isset($inquiryData['numberOfTravelers0']) ? $inquiryData['numberOfTravelers0'] : 0;
            //     $caseStats->pa_pax = $caseStats->group_pax_count = $numberOfTravelers12 + $numberOfTravelers2 + $numberOfTravelers0;
            // }
            if ($caseStats->pa_days == '') {
                $caseStats->pa_days = isset($inquiryData['tourLength']) ? $inquiryData['tourLength'] : '';
            }
            if ($caseStats->contact_addr_country == '') {
                $caseStats->contact_addr_country = isset($inquiryData['country']) ? $inquiryData['country'] : '';
            }
            // if ($caseStats->req_web_formula == '') {
            //     $caseStats->note = isset($inquiryData['extension']) ? $inquiryData['extension'] : '';
            // }
            if ($caseStats->req_web_tour == '') {
                $caseStats->req_web_tour = isset($inquiryData['tourName']) ? $inquiryData['tourName'] : '';
            }
            if ($caseStats->req_travel_type == '') {
                $req_travel_type = isset($inquiryData['typeGo']) ? $inquiryData['typeGo'] : '';
                switch ($req_travel_type) {
                    case 'En Famille':
                        $caseStats->req_travel_type = 'Family';
                        break;
                    case 'En Couple':
                        $caseStats->req_travel_type = 'Couple';
                        break;
                    case 'Entre Amis':
                        $caseStats->req_travel_type = 'Group';
                        break;
                    case 'Seul(e)':
                        $caseStats->req_travel_type = 'Solo';
                        break;
                }
            }
        }

        if ($caseStats->load(Yii::$app->request->post()) && $caseStats->validate()) {
            // \fCore::expose($_POST);
            // exit;
            //===thay thế cho trường note=====>>>>>>>>>> Anh Huân thay thế Chủ để Tuor bằng một trường nào đó $caseStats->theme_matic
            /* if ($caseStats->note != '') {
                $caseStats->note = implode(',', $caseStats->note);
            }*/
            $caseStats->save(false);
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $tourCountryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        return $this->render('kase_request', [
            'theCase'=>$theCase,
            'caseStats'=>$caseStats,
            'countryList'=>$countryList,
            'tourCountryList'=>$tourCountryList,
        ]);

    }

    // My frozen cases
    public function actionOnhold()
    {
        $theCases = Kase::find()
            ->where(['and', ['status'=>['onhold']], ['or', 'owner_id='.USER_ID, 'cofr='.USER_ID]])
            //->where(['and', ['status'=>['open', 'onhold']], ['or', 'owner_id=1677', 'cofr=1677']])
            ->orderBy('ao DESC, name')
            ->with(['tasks'=>function($q) {
                return $q->andWhere(['status'=>'on']);
                }
            ])
            ->asArray()
            ->limit(1000)
            ->all();

        return $this->render('cases_onhold', [
            'theCases'=>$theCases,
        ]);
    }

    public function actionC($from = '', $f_id = 0, $uid = 0)
    {
        if ($f_id > 0) {
            $id = $f_id;
        } else { $id = 0;}

        $theInquiry = false;
        $theMail = false;
        $dataUser = false;
        $theUser = new Person;
        $theUser->scenario = 'create';

        $theUserForm = new UsersUuForm();

        if ($from == 'inquiry') {
            $theInquiry = Inquiry::find()->with(['site'])->where(['id'=>$id])->asArray()->one();
            if (!$theInquiry) {
                throw new HttpException(404, 'Inquiry not found.');
            }

            if ($theInquiry['case_id'] != 0) {
                throw new HttpException(403, 'Inquiry has been linked to a case. Unlink it first.');
            }
            if ($uid > 0) {
                $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code, u.email FROM persons u WHERE u.id =:uid GROUP BY u.id ORDER BY u.fname, u.lname';
                $theUser = Person::findBySql($sql, [':uid' => $uid])->one();
                if (!$theUser) {
                    throw new HttpException(404, 'User not found.');
                }
                $theUser->scenario = 'update';
            } else {
                $theUser->email = $theInquiry['email'];
                if (Yii::$app->session->hasFlash('theUserForm')) {

                    $theUserForm = Yii::$app->session->getFlash('theUserForm');
                    var_dump($theUserForm);die;
                }
            }
        } elseif ($from == 'mail') {
            $theMail = Mail::find()->where(['id'=>$id])->asArray()->one();
            if (!$theMail) {
                throw new HttpException(404, 'Email not found.');
            }
            if ($theMail['case_id'] != 0) {
                throw new HttpException(403, 'Mail has been linked to a case. Unlink it first.');
            }
            if ($uid > 0) {
                $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.gender, u.country_code, u.email, u.phone FROM persons u WHERE u.id =:uid GROUP BY u.id ORDER BY u.fname, u.lname';
                $theUser = Person::findBySql($sql, [':uid' => $uid])->one();
                if (!$theUser) {
                    throw new HttpException(404, 'User not found.');
                }
                $theUser->scenario = 'update';
            } else {
                if (Yii::$app->session->hasFlash('email_new_case')) {
                    $theUser->email = Yii::$app->session->getFlash('email_new_case');
                    $theUser->lname = Yii::$app->session->getFlash('name_email_new_case');
                    Yii::$app->session->remove('email_new_case');
                    Yii::$app->session->remove('name_email_new_case');
                }
            }
        }
        $theCase = new Kase;
        $theCase->scenario = 'kase/c';

        $theCase->language = 'fr';
        $theCase->is_b2b = 'no';
        $theCase->is_priority = 'no';
        $theCase->company_id = 0;
        $theCase->owner_id = USER_ID;

        $theCase->created_at = NOW;
        $theCase->created_by = USER_ID;
        $theCase->updated_at = NOW;
        $theCase->updated_by = USER_ID;
        $theCase->status = 'open';
        $theCase->ao = NOW;
        $theCase->how_found = 'new/nref/web';

        $theCaseStats = new KaseStats;
        $theCaseStats->scenario = 'cases/request';



        if ($theInquiry) {
            $inquiryData = unserialize($theInquiry['data']);
            $theCase->how_contacted = 'web';
            if (isset($inquiryData['fname'], $inquiryData['lname'])) {
                if (isset($inquiryData['country']) && in_array($inquiryData['country'], ['vn', 'la', 'kh', 'cn'])) {
                    $theCase->name = ucwords(strtolower($inquiryData['fname'].' '.$inquiryData['lname']));
                } else {
                    $theCase->name = ucwords(strtolower($inquiryData['lname'].' '.$inquiryData['fname']));
                }
            } else {
                $theCase->name = ucwords(strtolower($theInquiry['name']));
            }
            if (isset($inquiryData['phone']) && $inquiryData['phone'] != '') {
                $theUser['phone'] = $inquiryData['phone'];
            }


            //set data to caseStats


        } elseif ($theMail) {
            $theCase->how_contacted = 'email';
            $theCase->how_found = '';
            $theCase->name = $theMail['from'];
        }
        if (!$theUser->id) {
            $theUser->id = 0;
        }
        if ($theCase->load(Yii::$app->request->post())
            && $theUser->load(Yii::$app->request->post())
            && $theCaseStats->load(Yii::$app->request->post())
            && $theCase->validate()
            // && $theUser->validate()
            && $theCaseStats->validate()
            )
        {


            if (substr($theCase['how_found'], 0, 7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                //$theCase['company_id'] = 0;
            }
            $theCase->save();
            // $theCaseLink = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];


            if ($theUser->id == 0) {
                $theUser->scenario = 'create';
                $theUser->id == null;
                $theUser->created_at = NOW;
                $theUser->created_by = MY_ID;
                $theUser->updated_at = NOW;
                $theUser->updated_by = MY_ID;
                $theUser->status = 'on';
                $theUser->fname = $theUser->fname != '' ? $theUser->fname : '-';
                $theUser->lname = $theUser->lname != '' ? $theUser->lname : '-';
                $theUser->name = $theUser->fname.' '.$theUser->lname;
                $theUser->phone = $theUser->phone != '' ? $theUser->phone : '-';
                $theUser->language = 'fr';
                $theUser->timezone = 'UTC';
                if ($theUser->save()) {
                    // Search
                    /*
                    $search = new Search;
                    $search->rtype = 'user';
                    $search->id = $theUser->id;
                    $search->search = strtolower(trim($theUser->fname.$theUser->lname.' '.$theUser->email.' '.$theUser->phone));
                    $search->found = trim($theUser->name.' '.$theUser->email.' '.$theUser->phone);
                    $search->save();
                    */
                    // Meta email
                    $meta = new Meta;
                    $meta->rtype = 'user';
                    $meta->rid = $theUser->id;
                    $meta->k = 'email';
                    $meta->v = $theInquiry['email'];
                    $meta->save();
                    // Meta phone
                    if ($theUser->phone != '') {
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'tel';
                        $meta->v = $theUser->phone;
                        $meta->save();
                    }
                    // Search
                    Yii::$app->db->createCommand()->insert('at_search', [
                        'rtype'=>'user',
                        'rid'=>$theUser->id,
                        'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                        'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])->execute();
                }
            } else {
                $theUser->scenario = 'update';
                $theUser->updated_at = NOW;
                $theUser->updated_by = MY_ID;
                $theUser->fname = $theUser->fname != '' ? $theUser->fname : '-';
                $theUser->lname = $theUser->lname != '' ? $theUser->lname : '-';
                $theUser->name = $theUser->fname.' '.$theUser->lname;
                $theUser->phone = $theUser->phone != '' ? $theUser->phone : '-';
                if ($theUser->save()) {
                    if ($theUser['email'] != '') {
                        // Meta email
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'email';
                        $meta->v = $theUser['email'];
                        $meta->save();
                    }
                    // Meta phone
                    if ($theUser->phone != '') {
                        $meta = new Meta;
                        $meta->rtype = 'user';
                        $meta->rid = $theUser->id;
                        $meta->k = 'tel';
                        $meta->v = $theUser->phone;
                        $meta->save();
                    }
                    // Search
                    Yii::$app->db->createCommand()->insert('at_search', [
                        'rtype'=>'user',
                        'rid'=>$theUser->id,
                        'search'=>\fURL::makeFriendly(trim($theUser->name.' '.$theUser->email.' '.$theUser->phone), ' '),
                        'found'=>trim($theUser->name.' '.$theUser->email.' '.$theUser->phone),
                    ])->execute();
                }
            }
            // Case stats
            $theCaseStats->updated_at = NOW;
            $theCaseStats->updated_by = USER_ID;
            $theCaseStats->case_id = $theCase['id'];
            $theCaseStats->save(false);

            // Insert case_user
            Yii::$app->db->createCommand()->insert('at_case_user', [
                'case_id' => $theCase['id'],
                'user_id' => $theUser['id'],
                'role'=>'contact',
            ])->execute();
            // Case search
            Yii::$app->db->createCommand()
                ->insert('at_search', [
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''),
                    'found'=>$theCase['name'],
                ])->execute();

            // Case ref
            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            // Update from
            if ($from == 'inquiry') {
                // Save email
                $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                // Link inquiry to case
                Yii::$app->db->createCommand()->update('at_inquiries', ['case_id'=>$theCase['id']], ['id'=>$id])->execute();
            } elseif ($from == 'mail') {
                    $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                    Yii::$app->db->createCommand($sql, [':email'=>$theUser['email'], ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                    // Link mails with same address to case
                    Yii::$app->db->createCommand()->update('at_mails', ['case_id'=>$theCase['id']])->execute();
            }

            // // Email people
            // if ($theCase['owner_id'] != 0) {
            //     $theOwner = Person::find()
            //         ->where(['id'=>$theCase['owner_id']])
            //         ->asArray()
            //         ->one();
            //     // User may not exist
            //     if (!$theOwner) {
            //         throw new HttpException(404, 'Case owner not found.');
            //     }

                // sys note
                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/assign',
                        'rtype'=>'case',
                        'rid'=>$theCase['owner_id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$theOwner['id'],
                    ])
                    ->execute();

            //     // Email owner
            //     if (USER_ID != $theOwner['id']) {
            //         $this->mgIt(
            //             'Case "'.$theCase['name'].'" has been assigned to you',
            //             '//mg/cases_assign',
            //             [
            //                 'theCase'=>$theCase,
            //             ],
            //             [
            //                 ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //                 ['to', $theOwner['email'], $theOwner['lname'], $theOwner['fname']],
            //                 // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //             ]
            //         );
            //     }
            // } // if case owner id
            // // Email people
            // if ($theCase['cofr'] == 13) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'bearez.hoa@amicatravel.com', 'Hoa', 'Bearez'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }
            // // Email people
            // if ($theCase['cofr'] == 5246) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'arnaud.l@amicatravel.com', 'Arnaud', 'Levallet'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }
            // // Email people
            // if ($theCase['cofr'] == 767) {
            //     $this->mgIt(
            //         'ims | Case "'.$theCase['name'].'" has been assigned to you',
            //         '//mg/cases_assign',
            //         [
            //             'theCase'=>$theCase,
            //         ],
            //         [
            //             ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
            //             ['to', 'vuong.xuan@amicatravel.com', 'Xuan', 'Vuong'],
            //             // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //         ]
            //     );
            // }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $ownerList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $cofrList = Person::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes', 'id'=>[13, 5246, 767]])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // $caseStats = KaseStats::find()
        //     ->where(['case_id'=>$theCase['id']])
        //     ->one();
        // var_dump($caseStats);die();
        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        $countrys = Country::find()
            ->select(['code', 'name_en'])
            // ->where(['code'=>['vn', 'la', 'kh', 'mm', 'id', 'my', 'th', 'cn']])
            ->orderBy('name_en')
            ->asArray()
            ->all();
        return $this->render('kase_u', [
            'theCase'=>$theCase,
            'theUser' => $theUser,
            'theCaseStats' => $theCaseStats,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
            'theInquiry'=>$theInquiry,
            'theMail'=>$theMail,
            'countryList' => $countryList,
            'countrys' => $countrys,
            'theUserForm' => $theUserForm,

        ]);
    }


    public function actionUnk_email(){
        $unknowns = Mail::find()
            ->where(['status'=>'on', 'case_id' => 0])->all();
        // var_dump(Yii::$app->user->identity->email);die();
        //hoa.nhung@amicatravel.com
        $email_ids = [];
        foreach ($unknowns as $theMail) {
            $emailFields = $theMail['from'].' '.$theMail['to'].' '.$theMail['cc'].' '.$theMail['bcc'];
            $emailAddresses = $this->getEmails($emailFields);
            $emails = [];
            foreach ($emailAddresses as $email) {

                if (strtolower($email) == 'ngo.hang@amicatravel.com' || strtolower($email) == Yii::$app->user->identity->email) {
                    $email_ids[] = $theMail['id'];
                    break;
                }
            }
        }
        $query = Mail::find()
            ->where(['id' => $email_ids, 'status' => 'on']);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);


        $theMails = $query
            ->orderBy('created_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return $this->render('kase_unk_email', [
            'pages'=>$pages,
            'theMails'=>$theMails
            ]
        );
    }
    public function actionR($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with([
                'owner',
                'people',
                'people.cases'=>function($q){
                    return $q->select(['id', 'created_at', 'status', 'name', 'deal_status', 'owner_id'])
                        ->orderBy('created_at DESC')
                        ->limit(5);
                },
                'people.cases.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'people.bookings',
                'people.bookings.product',
                //'people.tours'=>function($q) {
                    //return $q->select(['id', 'code']);
                //},
                'company',
                'createdBy',
                'updatedBy',
                'referrer',
                'stats',
                'tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tasks.assignees'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tasks.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'files',
                'bookings'=>function($q) {
                    return $q->orderBy('updated_at DESC');
                },
                'bookings.payments'=>function($q) {
                    return $q->orderBy('payment_dt');
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select('id, image');
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'title', 'day_ids', 'day_from', 'day_count']);
                },
                'bookings.product.tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'bookings.product.days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theCase) {
            var_dump($theCase);die('cases is empty!!');
            throw new HttpException(404, 'Case not found');
        }

        // Remove email mapping
        if (isset($_GET['action']) && $_GET['action'] == 'remove-email' && isset($_GET['email']) && in_array(USER_ID, [1, 4432, 26435, $theCase['owner_id']])) {
            $sql = 'DELETE FROM at_email_mapping WHERE case_id=:k AND email=:e';
            Yii::$app->db->createCommand($sql, [':k'=>$theCase['id'], ':e'=>$_GET['email']])->execute();
            return $this->redirect(DIR.URI);
        }

        // Add email mapping
        if (isset($_POST['email'])) {
            // Not owner
            if (!in_array(USER_ID, [1, 4432, 26435, $theCase['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
            }
            // Not open
            if ($theCase['status'] == 'closed') {
                //throw new HttpException(403, 'Case is not open.');
            }
            // Good to go
            $email = trim(strtolower($_POST['email']));
            $validator = new EmailValidator();
            if ($validator->validate($email, $error)) {
                // Save email
                // HS dong thi them email nhung khong them email rule
                if ($theCase['status'] != 'closed') {
                    $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                    Yii::$app->db->createCommand($sql, [':email'=>$email, ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                }
                // Add current
                $sql = 'UPDATE at_mails SET case_id=:case_id WHERE case_id=0 AND (from_email=:email OR to_email=:email)';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':case_id'=>$theCase['id']])->execute();
                // Return
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            } else {
                throw new HttpException(403, $error);
            }
        }

        $thePeople = User2::find()
            ->select(['id', 'name', 'fname', 'lname', 'email', 'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if (isset($_POST['body'])) {
            $utag = false;
            $itag = false;
            $title = isset($_POST['title']) ? trim($_POST['title']): '';
            $body = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);
            $title = trim($title, '#');


            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['nickname'].']';
                    if (strpos($_POST['to'], $mention) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:'.$person['email'];
                    $toEmail = 'to:'.$person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId = $person['id'];
                        $noteToId = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId = USER_ID;
                        $noteToId = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }


            /* OLD
            foreach ($thePeople as $person) {
                $mention = '@['.$person['name'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
                    $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
                    $toEmailList[] = $person['email'];
                    $toIdList[] = $person['id'];
                }
            }
            */
            $toEmailList = array_unique($toEmailList);

            /*          \fCore::expose($title);
                        \fCore::expose($body);
                        \fCore::expose($toEmailList);
                        exit;
            */
            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co = NOW;
            $theNote->cb = USER_ID;
            $theNote->uo = NOW;
            $theNote->ub = USER_ID;
            $theNote->status = 'on';
            $theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title = $title;
            $theNote->body = $_POST['body'];
            $theNote->rtype = 'case';
            $theNote->rid = $theCase['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }

            $sql = 'UPDATE at_cases SET last_accessed_dt=:now WHERE id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [':now'=>NOW, ':id'=>$theCase['id']])->execute();


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
            $relName = $theCase['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) &&  count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt = strrchr($newFileName, '.');
                    $rawFileName = $fileId.$rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot').'/assets/plupload_2.1.9/'.$rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'=>ICT,
                                'cb'=>USER_ID,
                                'uo'=>ICT,
                                'ub'=>USER_ID,
                                'name'=>$newFileName,
                                'ext'=>$rawFileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'case',
                                'rid'=>$theCase['id'],
                                'n_id'=>$theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot').'/upload/user-files/'.substr(ICT, 0, 7).'/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir.$newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                        Yii::$app->db->createCommand()
                            ->delete('at_files', [
                                'id'=>$newFileId,
                            ])
                            ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList.'<br>'.$body;
            }

            // Send email

            if (!empty($toEmailList)) {
                $subject = $title;
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Case: '.$relName;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-'.$theNote->id.'-'.USER_ID.'@amicatravel.com'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id=>$user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                var_dump($toList);die;
                // $this->mgIt(
                //     $subject,
                //     '//mg/note_added',
                //     [
                //         'toList'=>$toList,
                //         'theNote'=>$theNote,
                //         'relUrl'=>$relUrl,
                //         'body'=>$body,
                //     ],
                //     $args
                // );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $theCaseOwner = User2::find()->where(['id'=>$theCase['owner_id']])->one();

        if (Yii::$app->request->get('allnotes') == 'yes') {
            $tourIdList = [];
            foreach ($theCase['bookings'] as $booking) {
                if (isset($booking['product']['tour'])) {
                    $tourIdList[] = $booking['product']['tour']['id'];
                }
            }
            $theNotes = Note::find()
                ->where(['rtype'=>'case', 'rid'=>$id])
                ->orWhere(['rtype'=>'tour', 'rid'=>$tourIdList])
                ->with(['from', 'to'])
                ->orderBy('co DESC')
                ->all();
        } else {
            $theNotes = Note::find()
                ->where(['rtype'=>'case', 'rid'=>$id])
                ->with(['from', 'to'])
                ->orderBy('co DESC')
                ->all();
        }

        $caseInquiries = Inquiry::find()
            ->where(['case_id'=>$id])
            ->with(['site'])
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'nickname', 'name', 'image']);
                }
            ])
            ->asArray()
            ->all();

        // Email mapping: emails for auto-import
        $sql = 'SELECT email FROM at_email_mapping WHERE case_id=:id ORDER BY email';
        $theEmails = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryColumn();
        $kNote = Meta::find()
            ->with([
                'createdBy',
                'updatedBy'
            ])
            ->where(['name' => 'note', 'rtype' => 'case', 'rid' => $theCase['id']])
            ->asArray()
            ->one();
        // var_dump($note);die;
        $allCountries = Country::find()->select([
            'code',
            'dial_code',
            'name'=>'CONCAT(name_en, " (", name_vi, ")")',
            'name_vi',
            ])->asArray()->all();
        return $this->render('kase_r', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
            'kNote' => $kNote,
        ]);
    }
    /*
    *Add new / update / delete Note from kase via ajax
    *
    *
    */
    public function actionNote_case($note_id = 0, $body = '', $action = 'add', $case_id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $theUser = User2::find()
                ->select(['id', 'name'=>'nickname'])
                ->where(['status'=>'on'])
                ->andWhere(['id' => USER_ID])
                ->asArray()
                ->one();
            if ($note_id == 0 && $action == 'add') {
                $theCase = Kase::findOne($case_id);
                if (!$theCase) {
                    return json_encode(['err' => 'Kase not found!']);
                }
                $theNote = new Meta;
                $theNote->created_dt = NOW;
                $theNote->created_by = USER_ID;
                $theNote->name = 'note';
                $theNote->rtype = 'case';
                $theNote->rid = $case_id;
                $theNote->value = $body;
                if ($theNote->save(false)) {
                    return json_encode(['note' => ArrayHelper::toArray($theNote), 'who' => $theUser['name']]);
                } else {
                    return json_encode(['err' => $theNote->errors]);
                }
            }
            if ($note_id != 0 && $action == 'update') {
                $theNote = Meta::findOne($note_id);
                $theNote->updated_dt = NOW;
                $theNote->updated_by = USER_ID;
                $theNote->value = $body;

                if ($theNote->save(false)) {
                    return json_encode(['note' => ArrayHelper::toArray($theNote), 'who' => $theUser['name']]);
                } else {
                    return json_encode(['err' => $theNote->errors]);
                }
            }
            return json_encode(['err' => 'invalid']);
        }
    }

    /*
    *Add new / update Note from kase via ajax
    * add, remove files uploaded
    *
    */
    public function actionAjax($id)
    {
        if (!Yii::$app->request->isAjax) {
            return json_decode(['errr' => 'Request invalid']);
        }
        $action = ($_POST['action'])? $_POST['action']: '';
        $note_id = ($_POST['note_id'])? $_POST['note_id']: 0;
        $title = ($_POST['title'])? $_POST['title']: '';
        $kase_id = ($_POST['kase_id'])? $_POST['kase_id']: 0;
        $body = ($_POST['body'])? $_POST['body']: '';
        $files_added = ($_POST['files_added'])? $_POST['files_added']: [];
        $files_removed = ($_POST['files_removed'])? $_POST['files_removed']: [];

        $theCase = Kase::find()
            ->where(['id'=>$kase_id])
            ->asArray()
            ->one();

        if (!$theCase) {
            return json_encode(['err' => 'Case not found']);
        }
                // Save seller's note in a case workspace
        if ($action == 'save-knote' && $kase_id != 0) {
            // TODO check permission
            $theMeta = Meta::find()
                ->where(['rtype'=>'case', 'rid'=>$kase_id, 'name'=>'knote'])
                ->one();
            if (!$theMeta) {
                $theMeta = new Meta;
                $theMeta->rtype = 'case';
                $theMeta->rid = $case_id;
                $theMeta->name = 'knote';
                $theMeta->created_dt = NOW;
                $theMeta->created_by = USER_ID;
            }
            $theMeta->updated_dt = NOW;
            $theMeta->updated_by = USER_ID;
            $theMeta->value = $_POST['knote_body'] ?? '';
            $theMeta->save(false);
            return [
                'status'=>'ok',
                'code'=>200,
                'message'=>'OK.',
            ];
        }

        if ($action == 'save-message' && $body != '' && $title != '') {
            $thePeople = User2::find()
                ->select(['id', 'name', 'fname', 'lname', 'email', 'nickname'])
                ->where(['status'=>'on'])
                ->orderBy('lname, fname')
                ->asArray()
                ->all();
            $utag = false;
            $itag = false;

            if (strpos($title, '#urgent') !== false) {
                $utag = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);
            $title = trim($title, '#');


            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            foreach ($thePeople as $person) {
                $mention = '@'.$person['nickname'];
                if (strpos($body, $mention) !== false) {
                    $toList[$person['id']] = $person;
                    $toEmailList[] = $person['email'];
                    $toIdList[] = $person['id'];
                }
            }
            foreach ($theCase['people'] as $person) {
                $fromEmail = 'from:'.$person['email'];
                $toEmail = 'to:'.$person['email'];
                if (strpos($body, $fromEmail) !== false) {
                    $noteFromId = $person['id'];
                    $noteToId = USER_ID;
                    $noteViaEmail = true;
                } elseif (strpos($body, $toEmail) !== false) {
                    $noteFromId = USER_ID;
                    $noteToId = $person['id'];
                    $noteViaEmail = true;
                }
            }
            $toEmailList = array_unique($toEmailList);
            $toIdList = array_unique($toIdList);

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));
            if ($note_id > 0) {
                $theNote = Note::findOne($note_id);
                $theNote->uo = NOW;
                $theNote->ub = USER_ID;
            } else {
                $theNote = new Note;
                $theNote->scenario = 'notes_c';
                $theNote->co = NOW;
                $theNote->cb = USER_ID;
                $theNote->uo = NOW;
                $theNote->ub = USER_ID;
            }
            $theNote->status = 'on';
            $theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                if ($utag) {
                    $theNote->priority = 'C3';
                }else {
                    $theNote->priority = 'C1';
                }
            }
            if (!$itag) {
                if ($utag) {
                    $theNote->priority = 'A3';
                }
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title = $title;
            $theNote->body = $body;
            $theNote->rtype = 'case';
            $theNote->rid = $theCase['id'];

            if (!$theNote->save(false)) {
                return json_encode(['err' => 'NOTE NOT SAVED']);
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
            $relName = $theCase['name'];

            // Upload files
            $fileList = '';
            if (is_array($files_added) &&  count($files_added) > 0) {
                foreach ($files_added as $file) {
                    $newFileName = $file;
                    $rawFileExt = strrchr($newFileName, '.');
                    $rawFilePath = Yii::getAlias('@webroot').'/assets/plupload_2.1.9/'.$newFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'=>ICT,
                                'cb'=>USER_ID,
                                'uo'=>ICT,
                                'ub'=>USER_ID,
                                'name'=>$newFileName,
                                'ext'=>$rawFileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'case',
                                'rid'=>$theCase['id'],
                                'n_id'=>$theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot').'/upload/user-files/'.substr(ICT, 0, 7).'/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir.$newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                        Yii::$app->db->createCommand()
                            ->delete('at_files', [
                                'id'=>$newFileId,
                            ])
                            ->execute();
                        }
                    }
                }
            }
            if ($fileList != '') {
                $body = $fileList.'<br>'.$body;
            }

            //remove files
            if (is_array($files_removed) && count($files_removed) > 0) {
                foreach ($files_removed as $file_id) {
                    $theFile = Yii::$app->db->createCommand("SELECT * FROM at_files where id = ".$file_id)->queryOne();
                    $file_path = Yii::getAlias('@webroot').'/upload/user-files/'.substr($theFile['uo'], 0, 7).'/file-'.$theFile['cb'].'-'.$theFile['id'].'-'.$theFile['uid'];
                    if (!file_exists($file_path)) {
                        return json_encode(['err' => 'The file to remove is not found']);
                    }
                    unlink($file_path);
                }
            }

            // Send email

            if (!empty($toEmailList)) {
                $subject = $title;
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Case: '.$relName;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-'.$theNote->id.'-'.USER_ID.'@amicatravel.com'],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id=>$user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    $subject,
                    '//mg/note_added',
                    [
                        'toList'=>$toList,
                        'theNote'=>$theNote,
                        'relUrl'=>$relUrl,
                        'body'=>$body,
                    ],
                    $args
                );
            }

            return json_encode(['success' => 'Message is saved']);
        }
        throw new HttpException(401);
    }

    public function actionR2($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with([
                'owner',
                'people',
                'people.cases'=>function($q){
                    return $q->select(['id', 'created_at', 'status', 'name', 'deal_status', 'owner_id'])
                        ->orderBy('created_at DESC')
                        ->limit(5);
                },
                'people.cases.owner'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'people.bookings',
                'people.bookings.product',
                //'people.tours'=>function($q) {
                    //return $q->select(['id', 'code']);
                //},
                'company',
                'createdBy',
                'updatedBy',
                'referrer',
                'stats',
                'tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tasks.assignees',
                'files',
                'bookings'=>function($q) {
                    return $q->orderBy('updated_at DESC');
                },
                'bookings.payments'=>function($q) {
                    return $q->orderBy('payment_dt');
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select('id, image');
                },
                'bookings.product'=>function($q){
                    return $q->select(['id', 'title', 'day_ids', 'day_from', 'day_count']);
                },
                'bookings.product.tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'bookings.product.days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // Add email mapping
        if (isset($_POST['email'])) {
            // Not owner
            if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
                throw new HttpException(403, 'Access denied.');
            }
            // Not open
            if ($theCase['status'] == 'closed') {
                throw new HttpException(403, 'Case is not open.');
            }
            // Good to go
            $email = trim(strtolower($_POST['email']));
            $validator = new EmailValidator();
            if ($validator->validate($email, $error)) {
                // Save email
                $sql = 'INSERT INTO at_email_mapping (email, action, case_id) VALUES (:email, :action, :case_id) ON DUPLICATE KEY UPDATE case_id=:case_id';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':action'=>'add', ':case_id'=>$theCase['id']])->execute();
                // Add current
                $sql = 'UPDATE at_mails SET case_id=:case_id WHERE case_id=0 AND (from_email=:email OR to_email=:email)';
                Yii::$app->db->createCommand($sql, [':email'=>$email, ':case_id'=>$theCase['id']])->execute();
                // Return
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            } else {
                throw new HttpException(403, $error);
            }
        }

        $thePeople = User2::find()
            ->select(['id', 'name', 'fname', 'lname', 'email'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if (isset($_POST['body'])) {
            $utag = false;
            $itag = false;
            $title = isset($_POST['title']) ? trim($_POST['title']): '';
            $body = $_POST['body'];

            if (strpos($title, '#urgent') !== false) {
                $utag = true;
                $title = str_replace('#urgent', '', $title);
            }
            if (strpos($title, '#important') !== false) {
                $itag = true;
                $title = str_replace('#important', '', $title);
            }

            $title = trim($title);
            $title = trim($title, '#');


            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['name'].']';
                    if (strpos($_POST['to'], $mention) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($theCase['people'] as $person) {
                    $fromEmail = 'from:'.$person['email'];
                    $toEmail = 'to:'.$person['email'];
                    if (strpos($_POST['to'], $fromEmail) !== false) {
                        $noteFromId = $person['id'];
                        $noteToId = USER_ID;
                        $noteViaEmail = true;
                    } elseif (strpos($_POST['to'], $toEmail) !== false) {
                        $noteFromId = USER_ID;
                        $noteToId = $person['id'];
                        $noteViaEmail = true;
                    }
                }
            }
            /* OLD
            foreach ($thePeople as $person) {
                $mention = '@['.$person['name'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], 'https://my.amicatravel.com/users/r/'.$person['id']), $body);
                    $_POST['body'] = str_replace($mention, '@[user-'.$person['id'].']', $_POST['body']);
                    $toEmailList[] = $person['email'];
                    $toIdList[] = $person['id'];
                }
            }
            */
            $toEmailList = array_unique($toEmailList);

/*          \fCore::expose($title);
            \fCore::expose($body);
            \fCore::expose($toEmailList);
            exit;
*/
            // Save note

            define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

            $theNote = new Note;
            $theNote->scenario = 'notes_c';

            $theNote->co = NOW;
            $theNote->cb = USER_ID;
            $theNote->uo = NOW;
            $theNote->ub = USER_ID;
            $theNote->status = 'on';
            $theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
            $theNote->priority = 'A1';
            if ($itag) {
                $theNote->priority = 'C1';
            }
            if ($utag) {
                $theNote->priority = 'A3';
            }
            $theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : USER_ID;
            $theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
            $theNote->title = $title;
            $theNote->body = $_POST['body'];
            $theNote->rtype = 'case';
            $theNote->rid = $theCase['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_messages_to', ['n_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];
            $relName = $theCase['name'];

            // Upload files
            $fileList = '';
            if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) &&  count($_POST['fileid']) == count($_POST['filename'])) {
                foreach ($_POST['fileid'] as $i => $fileId) {
                    $newFileName = $_POST['filename'][$i];
                    $rawFileExt = strrchr($newFileName, '.');
                    $rawFileName = $fileId.$rawFileExt;
                    $rawFilePath = Yii::getAlias('@webroot').'/assets/plupload_2.1.9/'.$rawFileName;
                    if (file_exists($rawFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($rawFilePath);
                        $imgSize = @getimagesize($rawFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }
                        Yii::$app->db->createCommand()
                            ->insert('at_files', [
                                'co'=>ICT,
                                'cb'=>USER_ID,
                                'uo'=>ICT,
                                'ub'=>USER_ID,
                                'name'=>$newFileName,
                                'ext'=>$rawFileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'case',
                                'rid'=>$theCase['id'],
                                'n_id'=>$theNote['id'],
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $newDir = Yii::getAlias('@webroot').'/upload/user-files/'.substr(ICT, 0, 7).'/';
                        @mkdir($newDir);

                        // New name
                        $newName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;

                        // Move upload file to new (official) location
                        if (copy($rawFilePath, $newDir.$newName)) {
                            unlink($rawFilePath);
                            $fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
                            //echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
                        } else {
                        Yii::$app->db->createCommand()
                            ->delete('at_files', [
                                'id'=>$newFileId,
                            ])
                            ->execute();
                        }
                    }
                }
            }

            if ($fileList != '') {
                $body = $fileList.'<br>'.$body;
            }

            // Send email

            if (!empty($toEmailList)) {
                $subject = $title;
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Case: '.$relName;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->name, 'Amica Travel'],
                    ['reply-to', Yii::$app->user->identity->email],
                    // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($toList as $id=>$user) {
                    $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
                }
                $this->mgIt(
                    'ims | '.$subject,
                    '//mg/note_added',
                    [
                        'toList'=>$toList,
                        'theNote'=>$theNote,
                        'relUrl'=>$relUrl,
                        'body'=>$body,
                    ],
                    $args
                );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$theCase['id']])
            ->asArray()
            ->all();

        $theCaseOwner = User2::find()->where(['id'=>$theCase['owner_id']])->one();
        $theNotes = Note::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with(['from', 'to'])
            ->orderBy('co DESC')
            ->all();

        $caseInquiries = Inquiry::find()
            ->where(['case_id'=>$id])
            ->with(['site'])
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'case', 'rid'=>$id])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                }
            ])
            ->asArray()
            ->all();

        // Email mapping: emails for auto-import
        $sql = 'SELECT email FROM at_email_mapping WHERE case_id=:id ORDER BY email';
        $theEmails = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryColumn();

        $allCountries = Country::find()->select('code', 'name_vi')->asArray()->all();

        if (USER_ID == 111) {
        return $this->render('cases_r_huan', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
        ]);
        }
        return $this->render('cases_r2', [
            'theCase'=>$theCase,
            'theCaseOwner'=>$theCaseOwner,
            'caseInquiries'=>$caseInquiries,
            'theSysnotes'=>$theSysnotes,
            'theNotes'=>$theNotes,
            'inboxMails'=>$inboxMails,
            'thePeople'=>$thePeople,
            'theEmails'=>$theEmails,
            'allCountries'=>$allCountries,
        ]);
    }

    public function actionU($id = 0)
    {
        $theCase = Kase::findOne($id);
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        if ($theCase['is_b2b'] == 'yes') {
            return $this->redirect('/b2b/cases/u/'.$theCase['id']);
            exit;
        }

        // 140723: PhAnh và ThyNN edit nguon
        // 160616: Added Megan JB,
        // 160816: Added Hoa NTT,
        if (in_array(USER_ID, [695, 14671, 27510])) {
            return $this->redirect('@web/cases/upa/'.$id);
        }

        if (!in_array(USER_ID, [1,2,3,4, 4432, 11724, 36654, 26435, 35887, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        $oldOwnerId = $theCase['owner_id'];
        $oldRef = $theCase['ref'];
        $oldCofr = $theCase['cofr'];

        $theCase->scenario = 'kase/u';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            if (substr($theCase['how_found'], 0, 7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                // $theCase['company_id'] = 0;
            }
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->save(false);

            // Update search
            Yii::$app->db->createCommand()
                ->update('at_search', ['search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''), 'found'=>$theCase['name']], ['rtype'=>'case', 'rid'=>$theCase['id']])
                ->execute();

            $theCaseLink = 'https://my.amicatravel.com/cases/r/'.$theCase['id'];

            // Case search
            Yii::$app->db->createCommand()
                ->update('at_search', [
                    'search'=>$theCase['name'].' '.Inflector::slug($theCase['name'], ''),
                    'found'=>$theCase['name'],
                ], [
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                ]
                )->execute();

            // Case ref
            if ($theCase['ref'] != $oldRef  && $oldRef != 0) {
                Yii::$app->db->createCommand()
                    ->delete('at_referrals', [
                        'user_id'=>$oldRef,
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0  && $theCase['ref'] != $oldRef) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            // Email people
            if ($theCase['owner_id'] != 0 && $theCase['owner_id'] != $oldOwnerId) {
                // Owner may not exist
                $theOwner = User2::find()
                    ->where(['id'=>$theCase['owner_id']])
                    ->one();
                if (!$theOwner) {
                    throw new HttpException(404, 'Case owner not found.');
                }
                // New save
                $theCase->ao = NOW;
                $theCase->save(false);

                Yii::$app->db->createCommand()
                    ->insert('at_sysnotes', [
                        'created_at'=>NOW,
                        'user_id'=>USER_ID,
                        'action'=>'kase/assign',
                        'rtype'=>'case',
                        'rid'=>$theCase['id'],
                        'uri'=>URI,
                        'ip'=>USER_IP,
                        'info'=>$theOwner['id'],
                    ])
                    ->execute();

                // Email owner
                if (USER_ID != $theOwner['id']) {
                    $this->mgIt(
                        'ims | Case "'.$theCase['name'].'" has been assigned to you',
                        '//mg/cases_assign',
                        [
                            'theCase'=>$theCase,
                        ],
                        [
                            ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                            ['to', $theOwner['email'], $theOwner['lname'], $theOwner['fname']],
                            // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        ]
                    );
                }
            }

            // Email people
            if (in_array($theCase['cofr'], [13, 5246, 767]) && $theCase['cofr'] != $oldCofr) {
                $cofr = [
                    13=>['Hoa', 'Bearez', 'bearez.hoa@amicatravel.com'],
                    767=>['Xuan', 'Vuong', 'vuong.xuan@amicatravel.com'],
                    5246=>['Arnaud', 'Levallet', 'arnaud.l@amicatravel.com'],
                ];
                $this->mgIt(
                    'ims | Case "'.$theCase['name'].'" has been assigned to you',
                    '//mg/cases_assign',
                    [
                        'theCase'=>$theCase,
                    ],
                    [
                        ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                        ['to', $cofr[$theCase['cofr']][2], $cofr[$theCase['cofr']][0], $cofr[$theCase['cofr']][1]],
                    ]
                );
            }

            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }


        $ownerList = User2::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orWhere(['id'=>$theCase['id']])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $cofrList = User2::find()
            ->select(['id', 'CONCAT(lname, ", ", email) AS name'])
            ->where(['status'=>'on', 'is_member'=>'yes', 'id'=>[13, 5246, 767, $theCase['cofr']]])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('kase_u', [
            'theCase'=>$theCase,
            'ownerList'=>$ownerList,
            'cofrList'=>$cofrList,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
        ]);
    }

    // 140723: PhAnh và ThyNN edit nguon HS
    public function actionUpa($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // 150921 Added Sophie N
        // 160614 Added Megan JB
        if (!in_array(USER_ID, [1,695,14671,33776,27510])) {
            throw new HttpException(403, 'Access denied.');
        }

        $oldOwnerId = $theCase['owner_id'];
        $oldRef = $theCase['ref'];
        $oldCofr = $theCase['cofr'];

        $theCase->scenario = 'kase/upa';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            if (substr($theCase['how_found'], 0,7) != 'new/ref') {
                $theCase['ref'] = 0;
            }
            if ($theCase['how_contacted'] != 'agent') {
                // $theCase['company_id'] = 0;
            }
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->save(false);

            // Case ref
            if ($theCase['ref'] != $oldRef  && $oldRef != 0) {
                Yii::$app->db->createCommand()
                    ->delete('at_referrals', [
                        'user_id'=>$oldRef,
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            if (substr($theCase['how_found'], 0, 7) == 'new/ref' && $theCase['ref'] != 0  && $theCase['ref'] != $oldRef) {
                Yii::$app->db->createCommand()
                    ->insert('at_referrals', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'status'=>'draft',
                        'user_id'=>$theCase['ref'],
                        'case_id'=>$theCase['id'],
                    ])->execute();
            }

            return $this->redirect('@web/cases');
        }

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $campaignList = Campaign::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('kase_upa', [
            'theCase'=>$theCase,
            'companyList'=>$companyList,
            'campaignList'=>$campaignList,
        ]);
    }

    // Put a case on hold
    public function actionHold($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            //->asArray()
            ->one();
        if (!in_array(USER_ID, [1, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'open') {
            throw new HttpException(403, 'Case is not open.');
        }

        $theCase->status = 'onhold';
        $theCase->save(false);

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Put a case on hold
    public function actionUnhold($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            //->asArray()
            ->one();
        if (!in_array(USER_ID, [1, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'onhold') {
            throw new HttpException(403, 'Case is not on hold.');
        }

        $theCase->status = 'open';
        $theCase->save(false);

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Close a case
    public function actionClose($id = 0)
    {
        $theCase = Kase::findOne($id);
        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        if (!in_array(USER_ID, [1,2,3,4, 4432, 26435, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] == 'closed') {
            throw new HttpException(403, 'Case is already CLOSED.');
        }

        $theCase->scenario = 'kase/close';

        if ($theCase->load(Yii::$app->request->post()) && $theCase->validate()) {
            $theCase->updated_at = NOW;
            $theCase->updated_by = USER_ID;
            $theCase->status = 'closed';
            if ($theCase->deal_status == 'pending') {
                $theCase->deal_status = 'lost';
            }
            $theCase->status_date = NOW;
            $theCase->closed = NOW;
            $theCase->save(false);
            $this->mgIt(
                'ims | Case "'.$theCase['name'].'" was closed by '.Yii::$app->user->identity->name.' for reason: '.$theCase['why_closed'],
                '//mg/kase_close',
                [
                    'theCase'=>$theCase,
                ],
                [
                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                    ['to', 'ngo.hang@amica-travel.com', 'Hằng', 'Ngô'],
                ]
            );
            Yii::$app->db->createCommand()
                ->insert('at_sysnotes', [
                    'created_at'=>NOW,
                    'user_id'=>USER_ID,
                    'action'=>'kase/close',
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'uri'=>URI,
                    'ip'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
                    'info'=>$theCase['closed_note'].' : '.$theCase['why_closed'],
                ])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('at_email_mapping', ['case_id'=>$theCase['id']])
                ->execute();
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('kase_close', [
            'theCase'=>$theCase,
        ]);
    }

    // Reopen a case
    public function actionReopen($id = 0)
    {
        $theCase = Kase::findOne($id);
        if (!$theCase) {
            throw new HttpException(404, 'Case not found.');
        }

        if (!in_array(USER_ID, [1,2,3,4, 4432, 26435, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($theCase['status'] != 'closed' && $theCase['status'] != 'onhold') {
            throw new HttpException(403, 'Case is already OPEN');
        }

        if (Yii::$app->request->isPost && isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
            Yii::$app->db
                ->createCommand()
                ->update('at_cases', [
                    'updated_at'=>NOW,
                    'updated_by'=>USER_ID,
                    'status'=>'open',
                    'status_date'=>NOW,
                    // TODO: Neu HS da dong hon 6 thang thi ao= NOW,
                    'closed'=>'0000-00-00',
                    'deal_status'=>$theCase['deal_status'] == 'won' ? 'won' : 'pending',
                    ],[
                    'id'=>$id,
                    ])
                ->execute();
            Yii::$app->db->createCommand()
                ->insert('at_sysnotes', [
                    'created_at'=>NOW,
                    'user_id'=>USER_ID,
                    'action'=>'kase/reopen',
                    'rtype'=>'case',
                    'rid'=>$theCase['id'],
                    'uri'=>URI,
                    'ip'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
                    'info'=>'',
                ])
                ->execute();
            return Yii::$app->response->redirect('@web/cases/open');
        }
        return $this->render('cases_reopen', [
            'theCase'=>$theCase,
        ]);
    }

    // Delete case
    public function actionD($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }

        // Must be case owner
        if (!in_array(USER_ID, [1, 4432, 26435])) {
            throw new HttpException(403, 'Access denied');
        }

        // Do not delete case with existing booking
        $sql = 'SELECT COUNT(*) FROM  at_bookings WHERE case_id=:id';
        $cnt = Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->queryScalar();
        if ($cnt > 0) {
            throw new HttpException(403, 'Access denied: you must remove all bookings / proposals first.');
        }

        if (Yii::$app->request->post('confirm') == 'delete') {
            // Delete case user
            $sql = 'DELETE FROM at_case_user WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete tasks
            $sql = 'DELETE FROM at_tasks WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete inquiries
            $sql = 'DELETE FROM at_inquiries WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete mails
            $sql = 'DELETE FROM at_mails WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete notes
            $sql = 'DELETE FROM at_messages WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete files
            $sql = 'DELETE FROM at_files WHERE rtype="case" AND rid=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
            // Delete bookings
            /*
            $sql = 'DELETE FROM at_bookings WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
             Delete booking user
            $sql = 'DELETE FROM at_booking_user WHERE case_id=:id';
            Yii::$app->db->createCommand($sql, [':id'=>$theCase['id']])->execute();
*/
            $theCase->delete();
            return $this->redirect('@web/cases');
        }

        return $this->render('cases_d', [
            'theCase'=>$theCase,
        ]);
    }


    // Giới thiệu Mme Xuân
    public function actionMmeXuan($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->one();

        if (!in_array(USER_ID, [1, 1097, $theCase['owner_id']])) {
            throw new HttpException(403, 'You are not the owner.');
        }

        if ($theCase['status'] == 'closed') {
            throw new HttpException(403, 'Case is CLOSED. You have to reopen this case first.');
        }

        if (!in_array($theCase['at_who'], [0, 767])) {
            throw new HttpException(403, 'Case has been assigned to a different person other than Mme Xuan.');
        }

        if ($theCase['at_who'] == 0) {
            $theCase->at_who = 767;
            $theCase->at_opened = NOW;
            $theCase->save(false);
        } else {
            $theCase->at_who = 0;
            $theCase->at_opened = 0;
            $theCase->at_closed = 0;
            $theCase->save(false);
        }

        return $this->redirect('@web/cases/r/'.$theCase['id']);
    }

    // Add or remove people
    public function actionPeople($id = 0)
    {
        $theCase = Kase::find()
            ->where(['id'=>$id])
            ->with(['people'])
            ->asArray()
            ->one();
        if ($theCase['status'] == 'closed') {
            //throw new HttpException(403, 'Case is closed');
        }
        if (!$theCase) {
            throw new HttpException(404, 'Case not found');
        }
        if (!in_array(USER_ID, [1, 4432, $theCase['owner_id']])) {
            throw new HttpException(403, 'Access denied');
        }

        // Add a person
        if (Yii::$app->request->isPost && isset($_POST['action'], $_POST['user']) && $_POST['action'] == 'add') {
            $alreadyAdded = false;
            foreach ($theCase['people'] as $user) {
                if ($user['id'] == $_POST['user']) {
                    $alreadyAdded = true;
                    break;
                }
            }
            if (!$alreadyAdded) {
                $sql = 'INSERT INTO at_case_user (case_id, user_id, role) VALUES (:case_id, :user_id, "contact")';
                Yii::$app->db->createCommand($sql, [':case_id'=>$theCase['id'], ':user_id'=>$_POST['user']])->execute();
                return $this->redirect('@web/cases/r/'.$theCase['id']);
            }
        }

        // Remove a person
        if (isset($_GET['action'], $_GET['user']) && $_GET['action'] == 'remove') {
            $sql = 'DELETE FROM at_case_user WHERE case_id=:case_id AND user_id=:user_id';
            Yii::$app->db->createCommand($sql, [':case_id'=>$theCase['id'], ':user_id'=>$_GET['user']])->execute();
            return $this->redirect('@web/cases/r/'.$theCase['id']);
        }

        return $this->render('cases_people', [
            'theCase'=>$theCase,
        ]);
    }

    // Case request device
    public function actionUpdateCasesRequestDevice($year = 0)
    {
        if ($year == 0 || strlen($year) != 4) {
            $year = date('Y');
        }
        $theCases = Kase::find()
            ->select(['id', 'name'])
            ->where('SUBSTRING(created_at,1,4)=:y', [':y'=>$year])
            ->with([
                'stats',
                'inquiries'=>function($q) {
                    return $q->select(['ip', 'ua', 'case_id']);
                }
                ])
            ->asArray()
            ->all();
        foreach ($theCases as $case) {
            echo '<br>', $case['name'], ' : ';
            $device = 'none';
            foreach ($case['inquiries'] as $inquiry) {
                echo ' - UA: ', $inquiry['ua'];
                $detect = new \Mobile_Detect;
                $detect->setUserAgent($inquiry['ua']);
                $device = 'desktop';
                if ($detect->isMobile()) {
                    $device = 'mobile';
                }
                if ($detect->isTablet()) {
                    $device = 'tablet';
                }
            }
            echo ' --- <strong style="color:red">', $device, '</strong>';
            $sql = 'UPDATE at_case_stats SET request_device=:d WHERE case_id=:id LIMIT 1';
            Yii::$app->db->createCommand($sql, [
                ':d'=>$device,
                ':id'=>$case['id']
                ])->execute();
        }
    }
    // Extract email addresses from text
    private function getEmails($text)
    {
        $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i";
        preg_match_all($pattern, $text, $matches);
        // Get unique values
        return array_keys(array_flip($matches[0]));
    }
}
