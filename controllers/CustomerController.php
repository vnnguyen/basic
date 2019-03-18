<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\data\Pagination;
use common\models\Booking;
use common\models\Customer;
use common\models\Country;
use common\models\Person;
use common\models\Ct;
use common\models\Tour;
use common\models\Product;
use common\models\Task;
use common\models\Contact;
use common\models\ProfileCustomer;

use \PHPExcel;
use \PHPExcel_IOFactory;

class CustomerController extends MyController
{
    public function actions() {
        return [
            'index'=>[
                'class'=>'app\controllers\actions\customers\Index',
            ],
            'export-concat-files'=>[
                'class'=>'app\controllers\actions\customers\ExportConcatFiles',
            ],
        ];
    }
    public function actionAjax($action = '', $task_id = 0)
    {
        // 161011: Huan, Khang Ha, Bao Tuan, Pham Ha, Minh Minh
        // 161030: +Ngoc Anh
        if ($action == 'load_task_ac') {
            if (!in_array(USER_ID, [1, 1351, 12952, 27388, 29296, 30554])) {
                throw new HttpException(403, 'Denied');
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            $theTask = Task::find()
                ->where(['id'=>$task_id])
                ->asArray()
                ->one();
            if (!$theTask) {
                throw new HttpException(404, 'Task not found.');
            }

            $table = '';
            for ($i = 1; $i <= 4; $i ++) {
                $check = ' #'.$i;
                if (strpos($theTask['description'], $check)) {
                    $theTask['description'] = str_replace($check, '', $theTask['description']);
                    $table = $i;
                }
            }

            $icons = [];
            foreach ([' #t', ' #s', ' #q', ' #c'] as $check) {
                if (strpos($theTask['description'], $check)) {
                    $theTask['description'] = str_replace($check, '', $theTask['description']);
                    $icons[] = substr($check, -1);
                }
            }

            $time_fuzzy = 'e';
            $time = '09:00';
            if ($theTask['fuzzy'] == 'time' || $theTask['fuzzy'] == 'date') {
                $His = date('H:i:s', strtotime($theTask['due_dt']));
                if ($His == '11:59:59') {
                    $time_fuzzy = 'm';
                } elseif ($His == '17:59:59') {
                    $time_fuzzy = 'a';
                } elseif ($His == '23:59:59') {
                    $time_fuzzy = 'e';
                }
            } else {
                $time_fuzzy = 't';
                $time = date('H:i', strtotime($theTask['due_dt']));
            }
            $response = [
                'time_fuzzy'=>$time_fuzzy,
                'time'=>$time,
                'mins'=>$theTask['mins'],
                'table'=>$table,
                'icons'=>$icons,
                'note'=>trim(substr($theTask['description'], 2)),
            ];
            return $response;
        }

        if ($action == 'update_task_ac') {
            if (!in_array(USER_ID, [1, 1351, 12952, 27388, 29296, 30554])) {
                throw new HttpException(403, 'Denied');
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            $theTask = Task::find()
                ->where(['id'=>$task_id])
                ->one();
            if (!$theTask) {
                throw new HttpException(404, 'Task not found.');
            }


            if ($_POST['time_fuzzy'] == 'm') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'11:59:59';
                $time = 'Sáng';
            } elseif ($_POST['time_fuzzy'] == 'a') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'17:59:59';
                $time = 'Chiều';
            } elseif ($_POST['time_fuzzy'] == 'e') {
                $fuzzy = 'time';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).'23:59:59';
                $time = 'TBA';
            } else {
                $fuzzy = 'none';
                $due_dt = date('Y-m-d ', strtotime($theTask['due_dt'])).$_POST['time'].':00';
                $time = $_POST['time'];
            }
            $mins = (int)$_POST['mins'];
            $description = 'AC';
            $description .= ' '.$_POST['note'];
            if (in_array($_POST['table'], [1,2,3,4])) {
                $description .= ' #'.$_POST['table'];
            }
            if (isset($_POST['purpose']) && !empty($_POST['purpose'])) {
                foreach (['t', 's', 'q', 'c'] as $check) {
                    if (in_array($check, $_POST['purpose'])) {
                        $description .= ' #'.$check;
                    }
                }
            }

            $theTask->due_dt = $due_dt;
            $theTask->fuzzy = $fuzzy;
            $theTask->mins = $mins;
            $theTask->description = $description;
            $theTask->save(false);


            $showicons = [
                ' #t'=>'<i title="Thu/trả lại tiền" class="fa fa-fw fa-dollar text-success"></i>',
                ' #s'=>'<i title="Tổ chức sinh nhật" class="fa fa-fw fa-birthday-cake text-warning"></i>',
                ' #q'=>'<i title="Tặng quà sinh nhật" class="fa fa-fw fa-gift text-pink"></i>',
                ' #c'=>'<i title="Tặng quà khách cũ" class="fa fa-fw fa-user text-danger"></i>',
            ];
            $icons = '';
            foreach ([' #t', ' #s', ' #q', ' #c'] as $check) {
                if (strpos($theTask->description, $check)) {
                    $icons .= $showicons[$check];
                }
            }

            // Success
            $response = [
                'time'=>$time,
                'table'=>$_POST['table'],
                'icons'=>$icons,
                'note'=>$_POST['note'],
            ];
            return $response;
        }
    }

    public function actionIndex(
        $name = '',
        $gender = '',
        $age = '',
        $language = '',
        $country = '',
        $email = '',
        $tel = '',
        $address = '',

        array $profile = [], array $preferences = [], array $likes = [], array $dislikes = [], $amba = '',

        $year = '',
        $code = '',
        $output = 'view', // view or download
        $rcount = 0, // Number of referral cases
        $bcount = 0, // Number of bookings
        $downloadToken = ''
    )
    {
        session_write_close();
        ignore_user_abort(true);
        // HUAN fix missing profile customer
        if (USER_ID == 1 && isset($_GET['fixprofile'])) {
            $sql = 'SELECT user_id FROM at_booking_user GROUP BY user_id ORDER BY user_id DESC LIMIT 30000, 5000';
            $paxIdList = Yii::$app->db->createCommand($sql)->queryColumn();
            $profiles = ProfileCustomer::find()
                ->select(['id', 'user_id'])
                ->where(['user_id'=>$paxIdList])
                ->indexBy('user_id')
                ->asArray()
                ->all();
            foreach ($paxIdList as $paxId) {
                if (!isset($profiles[$paxId])) {
                    echo '<br>', $paxId, ' : NONE';
                    $profile = new ProfileCustomer;
                    $profile->created_dt = NOW;
                    $profile->updated_dt = NOW;
                    $profile->created_by = USER_ID;
                    $profile->updated_by = USER_ID;
                    $profile->user_id = $paxId;
                    $profile->save(false);
                }
            }
            // foreach ($paxIdList as $id) {
            //     echo '<br>', $id;
            //     $sql = 'SELECT id FROM at_profiles_customer WHERE user_id=:id LIMIT 1';
            //     $profileId = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryScalar();
            //     if (!$profileId) {
            //         echo ' -- NONE';
            //     }
            // }
            // \fCore::expose($paxIdList);
            exit;
        }

        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,4432,7756,26435,29296,30554,14671, 18598, 27388, 35071])) {
            //throw new HttpException(403, 'Access denied');
        }

        // if ($output == 'download' && !in_array(USER_ID, [1,695,4432,14671,18598])) {
        //     throw new HttpException(403, 'Access denied');
        // }


        $query = Contact::find()
            ->innerJoinWith([
                'profileCustomer',
                'bookings'=>function($q){
                    $q->select(['id', 'product_id']);
                },
                'bookings.product'=>function($q){
                    $q->select(['id', 'op_code', 'day_from', 'owner']);
                },
            ])
            ->groupBy('contacts.id');

        $personIdList = [];
        $searchById = false;

        if (strlen(trim($email)) > 2) {
            $searchById = true;
            $personIdListEmail = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'format'=>'email'])
                ->andWhere(['like', 'value', $email])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListEmail : array_intersect($personIdList, $personIdListEmail);
        }

        if (strlen(trim($tel)) > 2) {
            $searchById = true;
            $personIdListTel = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'format'=>'tel'])
                ->andWhere(['like', 'value', $tel])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListTel : array_intersect($personIdList, $personIdListTel);
        }

        if (strlen(trim($address)) > 2) {
            $searchById = true;
            $personIdListAddr = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'format'=>'address'])
                ->andWhere(['like', 'value', $address])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListAddr : array_intersect($personIdList, $personIdListAddr);
        }

        if (!empty($profile) && (int)$profile[0] > 0) {
            $searchById = true;
            $personIdListProfile = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'name'=>'traveler_profile'])
                ->andWhere('LOCATE(:profile, CONCAT("|", value, "|"))!=0', [':profile'=>'|'.$profile[0].'|'])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListProfile : array_intersect($personIdList, $personIdListProfile);

        }

        if (!empty($preferences) && (int)$preferences[0] > 0) {
            $searchById = true;
            $personIdListPrefs = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'name'=>'travel_preferences'])
                ->andWhere('LOCATE(:prefs, CONCAT("|", value, "|"))!=0', [':prefs'=>'|'.$preferences[0].'|'])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListPrefs : array_intersect($personIdList, $personIdListPrefs);
        }

        if (!empty($likes) && (int)$likes[0] > 0) {
            $searchById = true;
            $personIdListLikes = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'name'=>'likes'])
                ->andWhere('LOCATE(:like, CONCAT("|", value, "|"))!=0', [':like'=>'|'.$likes[0].'|'])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListLikes : array_intersect($personIdList, $personIdListLikes);
        }

        if (!empty($dislikes) && (int)$dislikes[0] > 0) {
            $searchById = true;
            $personIdListDislikes = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'name'=>'dislikes'])
                ->andWhere('LOCATE(:dislike, CONCAT("|", value, "|"))!=0', [':dislike'=>'|'.$dislikes[0].'|'])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListDislikes : array_intersect($personIdList, $personIdListDislikes);
        }

        if ($amba != '') {
            $searchById = true;
            $personIdListAmba = Meta::find()
                ->select(['rid'])
                ->where(['rtype'=>'user', 'name'=>'ambassaddor_potentiality'])
                ->andWhere(['value'=>$amba])
                ->asArray()
                ->column();
            $personIdList = empty($personIdList) ? $personIdListAmba : array_intersect($personIdList, $personIdListAmba);
        }


        if ((int)$rcount > 0) {
            $query->andWhere('won_referral_count>=:rcount', [':rcount'=>(int)$rcount]);
        }

        if ((int)$bcount > 0) {
            $query->andWhere('booking_count>=:bcount', [':bcount'=>(int)$bcount]);
        }

        if ($name != '') {
            $query->andWhere(['or', ['like', 'fname', $name], ['like', 'lname', $name], ['like', 'name', $name]]);
        }

        if (in_array($gender, ['male', 'female', 'other'])) {
            $query->andWhere(['gender'=>$gender]);
        }

        if ($age != '') {
            $thisYear = date('Y');
            $ageFromTo = explode('-', $age);
            if (is_array($ageFromTo) && count($ageFromTo) == 2) {
                $from = (int)$ageFromTo[0];
                $to = (int)$ageFromTo[1];
                $query->andWhere('byear<=:from', [':from'=>$thisYear - $from])->andWhere('byear>=:to', [':to'=>$thisYear - $to]);
            } else {
                if ((int)$age == 0) {
                    $query->andWhere(['byear'=>0]);
                } else {
                    $query->andWhere(['byear'=>$thisYear - $age]);
                }
            }
        }

        if (strlen($country) == 2) {
            $query->andWhere(['country_code'=>$country]);
        }

        if (strlen($language) == 2) {
            $query->andWhere(['contacts.language'=>$language]);
        }

        if (strlen($year) == 4 && (int)$year > 2006) {
            $query->andWhere('YEAR(day_from)=:year', [':year'=>$year]);
        }

        if (strlen($code) >= 4) {
            $query->andWhere(['like', 'op_code', $code]);
        }

        if ($searchById) {
            $query->andWhere(['contacts.id'=>$personIdList]);
        }

        $countryList = Country::find()
            ->select(['code', 'name_en'])
            ->orderBy('name_en')
            ->asArray()
            ->all();

        if (Yii::$app->request->get('output') == 'download') {
            $arr = ['ID', 'NAME-1', 'NAME-2', 'NAME', 'GENDER', 'COUNTRY', 'Age', 'Language', 'EMAIL', 'PHONE', 'ADDRESS', 'TOURS', 'Customer profile', 'Travel preferences', 'Likes', 'Dislikes', 'No. of bookings', 'No. of referrals'];

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $spreadsheet->getActiveSheet()->fromArray($arr, null, 'A1');
            $row = 2;

            $query->andWhere('at_ct.owner = "at"');
            $countQuery = clone $query;
            $pageSize = isset($pageSize) && $pageSize > 0 ? $pageSize : 1000;
            $limit = $countQuery->count() < $pageSize ? $countQuery->count(): $pageSize;
            if ($limit > 0) {
                $pages = ceil( $countQuery->count() / $limit );
                for ( $page = 0 ; $page < $pages ; $page++ ) {
                    $offset = $page * $limit;



                    $theCustomers = $query
                        ->with([
                            'bookings',
                            'bookings.product',
                            'metas',
                        ])
                        ->orderBy('lname, fname')
                        ->offset($offset)
                        ->limit($limit)
                        ->asArray()
                        ->all();
                    $languageList = [
                        'de'=>'Deutsch',
                        'en'=>'English',
                        'es'=>'Espanol',
                        'fr'=>'Francais',
                        'it'=>'Italiano',
                        'vi'=>'Tiếng Việt',
                        'zh'=>'中文',
                    ];

                    $customerProfileList = [
                        1=>Yii::t('p', 'Grand voyageur'),
                        2=>Yii::t('p', 'Backpacker'),
                        3=>Yii::t('p', 'Expatrié'),
                        4=>Yii::t('p', 'Origines Vietnamiennes'),
                        5=>Yii::t('p', 'Origines Laotiennes'),
                        6=>Yii::t('p', 'Origines Cambodgiennes'),
                        7=>Yii::t('p', 'Adoption d’un enfant en Asie du Sud-Est'),
                        8=>Yii::t('p', 'Membre d’une association : précisez'),
                        10=>Yii::t('p', 'Photographe professionnel'),
                        11=>Yii::t('p', 'Voyage avec un enfant en bas âge'),
                    ];

                    $travelPrefList = [
                        1=>Yii::t('p', 'Client très exigent'),
                        2=>Yii::t('p', 'Budget  critère principal'),
                        3=>Yii::t('p', 'Confort comme priorité'),
                        4=>Yii::t('p', 'Séjour Balnéaire'),
                        5=>Yii::t('p', 'Interaction Locale'),
                        6=>Yii::t('p', 'Aime le calme'),
                        7=>Yii::t('p', 'Pas de nuits chez l’Habitant'),
                        8=>Yii::t('p', 'Préférence pour hôtels de charme/boutique'),
                    ];

                    $likeList = [
                        1=>Yii::t('p', 'Photographie'),
                        2=>Yii::t('p', 'Vélo'),
                        3=>Yii::t('p', 'VTT'),
                        4=>Yii::t('p', 'Plongée sous-marine'),
                        5=>Yii::t('p', 'Snorkeling'),
                        6=>Yii::t('p', 'Sport nautiques'),
                        7=>Yii::t('p', 'Golf'),
                        8=>Yii::t('p', 'Equitation'),
                        9=>Yii::t('p', 'Yoga'),
                        10=>Yii::t('p', 'Danse'),
                        11=>Yii::t('p', 'Ski'),
                        12=>Yii::t('p', 'Autres sports'),
                        13=>Yii::t('p', 'Moto'),
                        14=>Yii::t('p', 'Gastronomie locale'),
                        15=>Yii::t('p', 'Nature, paysages, grands espaces'),
                        16=>Yii::t('p', 'Les sites culturels et monuments'),
                        17=>Yii::t('p', 'Artisanat'),
                        18=>Yii::t('p', 'Art et architecture'),
                        19=>Yii::t('p', 'Activités artistiques : théâtre, spectacles, expositions'),
                        20=>Yii::t('p', 'Musique'),
                        21=>Yii::t('p', 'Lecture'),
                        22=>Yii::t('p', 'Jardinage'),
                        23=>Yii::t('p', 'Plage et farniente'),
                        24=>Yii::t('p', 'Histoire'),
                        25=>Yii::t('p', 'Les rencontres'),
                        26=>Yii::t('p', 'Bateau'),
                        27=>Yii::t('p', 'Pêche'),
                        28=>Yii::t('p', 'Bricolage'),
                        29=>Yii::t('p', 'Archéologie'),
                        30=>Yii::t('p', 'Faune / sites animaliers'),
                        31=>Yii::t('p', 'Développement durable'),
                        32=>Yii::t('p', 'Shopping'),
                        33=>Yii::t('p', 'Marches / randonnées'),
                    ];

                    $dislikeList = [
                        1=>Yii::t('p', 'Les grandes villes'),
                        2=>Yii::t('p', 'La foule'),
                        3=>Yii::t('p', 'Trop de musées'),
                        4=>Yii::t('p', 'Trop de sites à visiter (temples, monuments…)'),
                        5=>Yii::t('p', 'Courir pendant le voyage'),
                        6=>Yii::t('p', 'Faire des trajets longs'),
                        7=>Yii::t('p', 'Sport / activité physique intense'),
                        8=>Yii::t('p', 'Le luxe, un confort standard suffit'),
                        9=>Yii::t('p', 'Arrêts shopping obligatoires'),
                        10=>Yii::t('p', 'Etre trop encadré pendant le voyage'),
                    ];
                    foreach ($theCustomers as $customer) {

                        $arr = [];
                        $arr[] = $customer['id'];
                        $arr[] = $customer['fname'];
                        $arr[] = $customer['lname'];
                        $arr[] = $customer['name'];
                        $arr[] = $customer['gender'];
                        $arr[] = $customer['country_code'];
                        $c_age = (int)date("Y") - (int)$customer['byear'];
                        $arr[] = $customer['byear'] > 0 ? $c_age: '';
                        $lang = '';
                        foreach ($countryList as $item) {
                            if ($item['code'] == substr($customer['language'], 0,2)) {
                                $lang = $item['name_en'];
                                break;
                            }
                        }
                        $arr[] = $lang;
                        $arr[] = $customer['email'];
                        $arr[] = $customer['phone'];
                        $cAddr = '';
                        foreach ($customer['metas'] as $meta) {
                            if ($meta['name'] == 'address') {
                                $cAddr = $meta['value'];
                                break;
                            }
                        }
                        $arr[] = $cAddr;
                        $tours = [];
                        foreach ($customer['bookings'] as $tour) {
                            $tours[] = $tour['product']['op_code'];
                        }
                        $arr[] = implode(', ', $tours);
                        $c_profile = $c_preferences = $c_like = $c_dislikes = [];
                        $referrals = 0;

                        foreach ($customer['metas'] as $meta) {
                            $m_arr = explode('|', $meta['value']);
                            if ($meta['name'] == 'traveler_profile') {
                                foreach ($m_arr as $v) {
                                    if (isset( $customerProfileList[trim($v)] )) {
                                        $c_profile[] = $customerProfileList[trim($v)];
                                    }
                                }
                                continue;
                            }
                            if ($meta['name'] == 'travel_preferences') {
                                foreach ($m_arr as $v) {
                                    if (isset( $travelPrefList[trim($v)] )) {
                                        $c_preferences[] = $travelPrefList[trim($v)];
                                    }
                                }
                                continue;
                            }
                            if ($meta['name'] == 'likes') {
                                foreach ($m_arr as $v) {
                                    if (isset( $travelPrefList[trim($v)] )) {
                                        $c_like[] = $travelPrefList[trim($v)];
                                    }
                                }
                                continue;
                            }
                            if ($meta['name'] == 'dislikes') {
                                foreach ($m_arr as $v) {
                                    if (isset( $travelPrefList[trim($v)] )) {
                                        $c_dislikes[] = $travelPrefList[trim($v)];
                                    }
                                }
                            }
                        }
                        $arr[] = implode(', ', $c_profile);
                        $arr[] = implode(', ', $c_preferences);
                        $arr[] = implode(', ', $c_like);
                        $arr[] = implode(', ', $c_dislikes);
                        $arr[] = $customer['profileCustomer']['booking_count'];
                        $arr[] = $customer['profileCustomer']['won_referral_count'];
                        $spreadsheet->getActiveSheet()->fromArray($arr, null, 'A'. $row);
                        $row ++;
                    }


                    // $theCptx = $query;
                    // foreach ($theTours as $tour) {
                    // }
                }
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=customers_list_'.date('Ymd-His'). '.Xlsx');

            sleep(6);
            setcookie("downloadToken", $downloadToken, time() + 20);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theCustomers = $query
            ->with([
                'bookings',
                'bookings.product',
                'metas',
            ])
            ->orderBy('lname, fname')
            ->offset($pagination->offset)
            ->limit($output == 'download' ? 5000 : $pagination->limit)
            ->asArray()
            ->all();


        if ($output == 'download') {
            $filename = 'customers_list_'.date('Ymd-His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename='.$filename);

            $out = fopen('php://output', 'w');
            fwrite($out, chr(239) . chr(187) . chr(191)); // BOM

            $arr = ['ID', 'NAME-1', 'NAME-2', 'NAME', 'GENDER', 'COUNTRY', 'BDAY', 'BMONTH', 'BYEAR', 'EMAIL', 'PHONE', 'ADDRESS', 'TOURS', 'TAGS'];
            fputcsv($out, $arr);

            foreach ($theUsers as $customer) {
                $arr = [];
                $arr[] = $customer['id'];
                $arr[] = $customer['fname'];
                $arr[] = $customer['lname'];
                $arr[] = $customer['name'];
                $arr[] = $customer['gender'];
                $arr[] = $customer['country_code'];
                $arr[] = $customer['bday'];
                $arr[] = $customer['bmonth'];
                $arr[] = $customer['byear'];
                $arr[] = $customer['email'];
                $arr[] = $customer['phone'];
                $cAddr = '';
                foreach ($customer['metas'] as $meta) {
                    if ($meta['k'] == 'address') {
                        $cAddr = $meta['v'];
                        break;
                    }
                }
                $arr[] = $cAddr;
                $tours = [];
                foreach ($customer['bookings'] as $tour) {
                    $tours[] = $tour['product']['op_code'];
                }
                $arr[] = implode(', ', $tours);
                $arr[] = '';
                fputcsv($out, $arr);
            }

            fclose($out);
            exit;
        }

        return $this->render('customer_index', [
            'name'=>$name,
            'gender'=>$gender,
            'age'=>$age,
            'language'=>$language,
            'country'=>$country,
            'email'=>$email,
            'tel'=>$tel,
            'address'=>$address,

            'profile'=>$profile,
            'preferences'=>$preferences,
            'likes'=>$likes,
            'dislikes'=>$dislikes,
            'amba'=>$amba,

            'pagination'=>$pagination,
            'theCustomers'=>$theCustomers,
            'year'=>$year,
            'code'=>$code,
            'countryList'=>$countryList,
            'bcount'=>$bcount,
            'rcount'=>$rcount,
        ]);
    }


    public function actionBirthdays($day = 0, $month = 0, $year = 0, $name = '')
    {
        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,7756,9881,29296,30554,14671, 18598, 27388])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($month == 0) {
            $month = date('n');
        }

        $query = Person::find()
            ->innerJoinWith(['bookings']);
        if ($year != 0) {
            $query->andWhere(['byear'=>$year]);
        }
        if ($month != 0) {
            $query->andWhere(['bmonth'=>$month]);
        }
        if ($day != 0) {
            $query->andWhere(['bday'=>$day]);
        }
        if (strlen(trim($name)) > 2) {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theUsers = $query
            ->select(['persons.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->with(['metas', 'bookings', 'bookings.product'])
            ->orderBy('bday, byear')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('customers_birthdays', [
            'pagination'=>$pagination,
            'theUsers'=>$theUsers,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'name'=>$name,
        ]);
    }

    public function actionPrintBirthdays($month = null)
    {
        if (!in_array(USER_ID, [1,2,3,4,11,118,695,4432,1351,7756,9881,29296,30554,14671, 18598])) {
            throw new HttpException(403, 'Access denied');
        }

        $getMonth = date('n');
        if (isset($month) && (int)$month <= 12) {
            $getMonth = $month;
        }

        $theUsers = Person::find()
            ->innerJoinWith(['bookings'])
            ->select(['persons.id', 'fname', 'lname', 'gender', 'country_code', 'bday', 'bmonth', 'byear'])
            ->where(['bmonth'=>$getMonth])
            ->with(['metas'])
            ->orderBy('bday, byear')
            ->asArray()
            ->all();

        return $this->render('customers_print-birthdays', [
            'theUsers'=>$theUsers,
            'getMonth'=>$getMonth,
        ]);
    }

    public function actionTasks($month = '')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $theTours = Product::find()
            ->select(['id', 'op_name', 'op_code', 'day_from', 'day_count', 'pax', 'op_finish'])
            ->with([
                'tour',
                'tour.tasks'=>function($q){
                    return $q->select(['description', 'status', 'due_dt', 'rid', 'rtype'])
                        ->where(['description'=>['PC', 'BV', 'AC', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'SV']])
                        ->orWhere('SUBSTRING(description,1,3) IN ("PC ","BV ","AC ","A1 ", "A2 ", "A3 ", "A4 ", "A5 ", "A6 ", "A7 ", "SV")');
                },
                'tour.cskh'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
                ])
            ->where(['op_status'=>'op'])
            ->andWhere('SUBSTRING(day_from,1,7)=:ym', [':ym'=>$month])
            ->orderBy('at_ct.day_from, at_ct.day_count')
            ->asArray()
            ->limit(1000)
            ->all();

            // \fCore::expose($theTours); exit;

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(ct.day_from,1,7) AS ym, SUBSTRING(ct.day_from,1,4) AS yr FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        return $this->render('customers_tasks', [
            'theTours'=>$theTours,
            'month'=>$month,
            'monthList'=>$monthList,
        ]);
    }

    public function actionTasksAc($date = '', $at = 'hanoi')
    {
        if (strlen($date) != 10) {
            $date = date('Y-m-d');
        }

        // if date is Sunday then we have to change to last week as code will give next Monday instead
        if (date('w', strtotime($date)) == 0) {
            $date = date('Y-m-d', strtotime('-1 days', strtotime($date)));
        }

        $thisWeek = date('Y-m-d', strtotime('this week', strtotime($date)));
        $prevWeek = date('Y-m-d', strtotime('-7 days', strtotime($thisWeek)));
        $nextWeek = date('Y-m-d', strtotime('+7 days', strtotime($thisWeek)));

        if ($at == 'hanoi') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND SUBSTRING(tk.description,1,5)!="AC SG" AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'saigon') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC SG ", tk.description)=1 OR tk.description="AC SG") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } elseif ($at == 'luangprabang') {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC LP ", tk.description)=1 OR tk.description="AC LP") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        } else {
            $theTours = Yii::$app->db->createCommand('SELECT t.id, t.code, t.status, t.name, t.se, t.ct_id, (SELECT nickname FROM persons u WHERE u.id=t.se LIMIT 1) AS se_name, ct.id AS ct_id, ct.day_from, ct.day_count, ct.day_ids, tk.id AS task_id, SUBSTRING(tk.due_dt,1,10) AS task_date, SUBSTRING(tk.due_dt,12,5) AS task_time, tk.description, tk.status AS task_status FROM at_ct ct, at_tours t, at_tasks tk WHERE ct.id=t.ct_id AND tk.rtype="tour" AND tk.rid=t.id AND (LOCATE("AC ", tk.description)=1 OR tk.description="AC") AND due_dt>=:monday AND due_dt<:sunday GROUP BY t.id ORDER BY tk.due_dt', [':monday'=>$thisWeek, ':sunday'=>$nextWeek])
                ->queryAll();
        }

        $tourIdList = [];
        foreach ($theTours as $li) {
            $tourIdList[] = $li['id'];
        }

        $ctIdList = [];
        foreach ($theTours as $li) {
            $ctIdList[] = $li['ct_id'];
        }

        $dayIdList = [];

        foreach ($theTours as $li) {
            $dt1 = new \DateTime($li['day_from']);
            $dt2 = new \DateTime($li['task_date']);
            $interval = $dt1->diff($dt2)->format('%a');
            $dayIdArray = explode(',', $li['day_ids']);
            if (isset($dayIdArray[abs($interval)])) {
                $dayIdList[] = $dayIdArray[abs($interval)];
            }
        }

        if (empty($dayIdList)) {
            $theDays = [];
        } else {
            $theDays = Yii::$app->db->createCommand('SELECT id, rid, name FROM at_days WHERE id IN ('.implode(',', $dayIdList).')')
                ->queryAll();
        }

        // Tim cac nhiem vu tour trong khoang ngay
        if ($at == 'hanoi') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC ", description)=1 OR description="AC") AND SUBSTRING(description,1,5)!="AC SG" AND SUBSTRING(description,1,5)!="AC LP" AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'saigon') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC SG ", description)=1 OR description="AC SG") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } elseif ($at == 'luangprabang') {
            $taskQuery = Task::find()
                ->where('(LOCATE("AC LP ", description)=1 OR description="AC LP") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        } else {
            // All locs
            $taskQuery = Task::find()
                ->where('(LOCATE("AC ", description)=1 OR description="AC") AND due_dt>=:monday AND due_dt<:sunday', [':monday'=>$thisWeek, ':sunday'=>$nextWeek]);
        }

        $theTasks = $taskQuery
            ->andWhere(['rtype'=>'tour'])
            ->with([
                'tour'=>function($q){
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.product'=>function($q){
                    return $q->select(['id', 'day_from', 'pax', 'day_count']);
                },
                'tour.product.pax'=>function($q){
                    return $q->select(['id', 'tour_id', 'name', 'pp_birthdate']);
                },
                'tour.cskh'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'phone']);
                },
                'tour.operators'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'phone']);
                },
                'tour.product.guides',
                'tour.product.bookings'=>function($q){
                    return $q->select(['id', 'product_id', 'created_by', 'pax']);
                },
                'tour.product.bookings.createdBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour.product.bookings.people'=>function($q){
                    return $q->select(['id', 'name', 'bday', 'bmonth']);
                },
                'tour.product.bookings.people.bookings'=>function($q){
                    return $q->select(['id', 'status'])->where(['status'=>'won']);
                },
                'tour.product.days'=>function($q) use ($dayIdList) {
                    return $q->select(['id', 'name', 'rid'])->where(['id'=>$dayIdList]);
                },
                ])
            ->orderBy('due_dt')
            ->asArray()
            ->all();

        return $this->render('customers_tasks-ac', [
            'theTasks'=>$theTasks,
            'thisWeek'=>$thisWeek,
            'prevWeek'=>$prevWeek,
            'nextWeek'=>$nextWeek,
            'at'=>$at,
        ]);
    }
}
