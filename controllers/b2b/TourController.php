<?

namespace app\controllers\b2b;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\Tour;
use common\models\TourStats;
use common\models\Product;
use common\models\Booking;
use common\models\Cp;
use common\models\Cpt;
use common\models\Cpg;
use common\models\Mail;
use common\models\Note;
use common\models\Pax;
use common\models\Sysnote;
use common\models\TourAcceptForm;
use common\models\TourAssignCsForm;
use common\models\TourRatingsForm;
use common\models\Tournote;
use common\models\TourDriverForm;
use common\models\TourSettingsForm;
use common\models\TourGuideForm;
use common\models\TourInCtForm;
use common\models\TourInLxForm;
use common\models\TourInHdForm;
use common\models\Person;
use common\models\Venue;
use common\models\Company;
use common\models\PrintWelcomeBannerForm;
use common\models\PrintFeedbackForm;
use \kartik\mpdf\Pdf;

class TourController extends \app\controllers\MyController
{
    public function actionIndex($view = 'normal', $orderby='startdate', $month = '', $fg = '', $status = '', $seller = 0, $operator = 0, $cservice = 0, $name = '', $dayname = '', $owner = '') {
        if ($month == 'next30days') {
            $dateRange = [date('Y-m-d'), date('Y-m-d', strtotime('+30 days'))];
        } elseif ($month == 'last30days') {
            $dateRange = [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')];
        } elseif (strlen($month) == 10) {
            $dateRange = [$month, date('Y-m-d', strtotime('+6 days '.$month))];
        } elseif (strlen($month) == 7) {
            $dateRange = [$month.'-01', date('Y-m-t', strtotime($month.'-01'))];
        } else {
            $month = date('Y-m');
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }

        $query = Product::find()
            ->where(['op_status'=>'op']);
        if ($orderby == 'enddate') {
            $query->select(['*', 'ed'=>new \yii\db\Expression('(SELECT DATE_ADD(day_from, INTERVAL day_count-1 DAY))')]);
        }

        if ($orderby == 'enddate') {
            $query->andHaving('ed BETWEEN :date1 AND :date2', [':date1'=>$dateRange[0], ':date2'=>$dateRange[1]]);
        } elseif ($orderby == 'startdate') {
            $query->andWhere('day_from BETWEEN :date1 AND :date2', [':date1'=>$dateRange[0], ':date2'=>$dateRange[1]]);
        } else {
            // Created
            $sql = 'SELECT ct_id FROM at_tours WHERE created_dt BETWEEN :date1 AND :date2 ORDER BY created_dt DESC';
            $tourIdList = Yii::$app->db->createCommand($sql, [':date1'=>$dateRange[0], ':date2'=>$dateRange[1]])->queryColumn();
            $query->select(['*', new \yii\db\Expression('(SELECT id FROM at_tours WHERE at_tours.ct_id=at_ct.id LIMIT 1) AS tour_old_id')]);
            $query->andWhere(['id'=>$tourIdList]);
        }

        if (strlen($name) > 2) {
            $query->andWhere(['like', 'op_name', $name]);
        }

        $theTours = $query
            ->andWhere('upper(substring(op_code, 1,1))="G"')
            ->orderBy($orderby == 'enddate' ? 'ed' : ($orderby == 'startdate' ? 'day_from' : 'tour_old_id DESC'))
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'ct_id', 'code', 'name', 'status', 'owner']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname', 'image']);
                },
                'tourStats',
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings',
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname', 'image'])->orderBy('lname, fname');
                },
                'tour.operators'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname', 'image'])->orderBy('lname, fname');
                },
                'pax'=>function($q){
                    return $q->select(['tour_id', 'is_repeating', 'name', 'pp_country_code', 'pp_birthdate', 'pp_gender']);
                },
            ])
            ->asArray()
            ->all();

        $sql = 'SELECT SUBSTRING(day_from,1,7) AS ym, COUNT(*) AS total FROM at_ct WHERE op_status="op" GROUP BY ym ORDER BY ym DESC';
        $monthList = Yii::$app->db->createCommand($sql)->queryAll();

        $tourIdList = [];
        $sellerList = [];
        foreach ($theTours as $tour) {
            $tourIdList[] = (int)$tour['tour']['id'];
            foreach ($tour['bookings'] as $booking) {
                $sellerList[$booking['createdBy']['id']] = $booking['createdBy']['name'];
            }
        }

        $operatorList = [];
        $cserviceList = [];
        $tourPeople = [];

        if (!empty($tourIdList)) {
            $sql = 'SELECT u.id, u.nickname AS name, tu.tour_id FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id IN ('.implode(', ', $tourIdList).')';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();

            $sql = 'SELECT u.id, u.nickname AS name, tu.tour_id FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id IN ('.implode(', ', $tourIdList).')';
            $cserviceList = Yii::$app->db->createCommand($sql)->queryAll();

            $sql = 'SELECT u.id, u.nickname AS name, tu.tour_id, tu.role FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role IN ("operator", "cservice") AND tu.tour_id IN ('.implode(', ', $tourIdList).')';
            $tourPeople = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $staffList = [];
        foreach ($tourIdList as $tourId) {
            $staffList[$tourId] = [
                'se'=>[],
                'op'=>[],
                'cs'=>[],
            ];
            foreach ($tourPeople as $person) {
                if ($person['tour_id'] == $tourId && $person['role'] == 'operator') {
                    $staffList[$tourId]['op'][] = $person['id'];
                }
                if ($person['tour_id'] == $tourId && $person['role'] == 'cservice') {
                    $staffList[$tourId]['cs'][] = $person['id'];
                }
            }
        }

        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }
        $tourOldIdList = [0];
        foreach ($theTours as $tour) {
            $tourOldIdList[] = (int)$tour['tour']['id'];
        }
        $sql = 'select tour_id, points, IF (guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u where u.id=guide_user_id limit 1)) AS namephone from at_tour_guides where parent_id=0 AND tour_id IN ('.implode(',', $tourIdList).')';
        $tourGuides = Yii::$app->db->createCommand($sql)->queryAll();
        $sql = 'select tour_id, points, IF (driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u where u.id=driver_user_id limit 1)) AS namephone from at_tour_drivers where parent_id=0 AND tour_id IN ('.implode(',', $tourIdList).')';
        $tourDrivers = Yii::$app->db->createCommand($sql)->queryAll();
        $sql = 'select tu.tour_id, u.id, u.nickname AS name, u.image FROM persons u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="operator" AND tu.tour_id IN ('.implode(',', $tourOldIdList).')';
        $tourOperators = Yii::$app->db->createCommand($sql)->queryAll();
        $sql = 'select tu.tour_id, u.id, u.nickname AS name, u.image FROM persons u, at_tour_user tu WHERE u.id=tu.user_id AND tu.role="cservice" AND tu.tour_id IN ('.implode(',', $tourOldIdList).')';
        $tourCCStaff = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('tour_index', [
            'theTours'=>$theTours,
            'tourGuides'=>$tourGuides,
            'tourDrivers'=>$tourDrivers,
            'tourOperators'=>$tourOperators,
            'tourCCStaff'=>$tourCCStaff,
            'month'=>$month,
            'fg'=>'g',
            'status'=>$status,
            'seller'=>$seller,
            'operator'=>$operator,
            'cservice'=>$cservice,
            'name'=>$name,
            'view'=>$view,
            'orderby'=>$orderby,
            'dayname'=>$dayname,
            'monthList'=>$monthList,
            'sellerList'=>$sellerList,
            'operatorList'=>$operatorList,
            'cserviceList'=>$cserviceList,
            'staffList'=>$staffList,
            'owner'=>$owner,
        ]);
    }

    public function actionR($id = 0)
    {
        $productId = Yii::$app->db->createCommand('SELECT ct_id FROM at_tours WHERE id=:id LIMIT 1', [':id'=>$id])->queryScalar();
        $theTour = Product::find()
            ->where(['id'=>$productId])
            ->with([
                'pax'=>function($q){
                    return $q->orderBy('booking_id, name');
                },
                'tournotes',
                'tourStats',
                'tournotes.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.createdBy',
                'bookings.case',
                'bookings.case.owner'=>function($q) {
                    return $q->select(['id', 'nickname']);
                },
                'bookings.invoices',
                'bookings.payments',
                'days',
                'tour',
                'tour.operators',
                'tour.cskh',
                'tour.guides',
                'tour.tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tour.tasks.assignees'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour.tasks.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }

        // Amica all people
        $thePeople = Person::find()
            ->select(['id', 'name', 'fname', 'lname', 'nickname', 'email'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        // Tour guides
        // $sql = 'SELECT u.id, u.fname, u.lname, u.phone AS uphone, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:tour_id ORDER BY day LIMIT 100';
        // $tourGuidesOld = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql = 'select * from at_tour_guides where tour_id=:tour_id limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Tour operators
        $sql = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:tour_id';
        $tourOperators = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id=:tour_id';
        $tourCSStaff = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour feedbacks
        $sql = 'SELECT * FROM at_tour_feedbacks WHERE tour_id=:tour_id ORDER BY id DESC LIMIT 100';
        $tourFeedbacks = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Pax list
        $bookingIdList = [];
        $tourPax = [];
        foreach ($theTour['bookings'] as $booking) {
            $bookingIdList[] = $booking['id'];
        }
        if (!empty($bookingIdList)) {
            $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.byear, u.bmonth, u.bday, u.gender, u.country_code, bu.booking_id FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.status!="canceled" AND bu.booking_id IN ('.implode(',', $bookingIdList).')';
            $tourPax = Yii::$app->db->createCommand($sql)->queryAll();
            // Tour reg info
            //$sql = 'SELECT booking_id, reg_confirmed_dt FROM at_client_page_links WHERE reg_confirmed_dt!=0 AND booking_id IN ('.implode(',', $bookingIdList).')';
            $tourRegInfo = []; //Yii::$app->db->createCommand($sql)->queryAll();
        }

        // List of case id
        $caseIdList = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']) {
                $caseIdList[] = $booking['case']['id'];
            }
        }


        // Tour referrals
        $tourRefs = [];
        if (!empty($caseIdList)) {
            $sql = 'SELECT r.*, u.name FROM at_referrals r, persons u WHERE u.id=r.user_id AND r.case_id IN ('.implode(',', $caseIdList).') LIMIT 100';
            $tourRefs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Post a note
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

            // $thePeople = $theTour['tour']['operators'];

            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['nickname'].']';
                    // 160107 Quick fix cho Ha beo khong email duoc Quynh Giang
                    $mentionEmail = strstr(str_replace('.', '', $person['email']), '@', true);
                    if (strpos($_POST['to'], $mention) !== false || strpos($_POST['to'], $mentionEmail) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($thePeople as $person) {
                //TODO: foreach ($theCase['people'] as $person) {
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
            $theNote->rtype = 'tour';
            $theNote->rid = $theTour['tour']['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/tours/r/'.$theTour['tour']['id'];
            $relName = $theTour['tour']['code'].' - '.$theTour['tour']['name'];

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
                                'rtype'=>'tour',
                                'rid'=>$theTour['tour']['id'],
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
                // Tour staff names, to include at the end of email subject
                $trail = $theTour['tour']['code'].' - '.$theTour['tour']['name'];
                if (count($theTour['bookings']) > 1) {
                    $trail .= ' (combined)';
                }

                $tourPaxCount = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourPaxCount[] = $booking['pax'];
                }
                $trail .= ' - '.implode('+', $tourPaxCount).'p ';
                $trail .= $theTour['day_count'].'d ';
                $trail .= date('j/n', strtotime($theTour['day_from'])).' - ';

                $tourStaff = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourStaff[] = $booking['createdBy']['nickname'];
                }
                foreach ($theTour['tour']['operators'] as $user) {
                    $tourStaff[] = $user['nickname'];
                }

                $trail .= implode(', ', $tourStaff);

                $subject = $theTour['tour']['code'].' - '.$title;
                $subject = str_replace($theTour['tour']['code'].' - '.$theTour['tour']['code'].' - ', $theTour['tour']['code'].' - ', $subject);
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Tour: '.$trail;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-'.$theNote->id.'-'.USER_ID.'@amicatravel.com'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
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
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$caseIdList])
            ->andWhere(['or', 'LOCATE("[cs]", subject)!=0', ['like', 'tags', 'op']])
            ->asArray()
            ->all();

        $theNotes = Note::find()
            ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
            ->with([
                'files',
                'from'=>function($q) {
                    return $q->select(['id', 'nickname', 'image']);
                },
                'to'=>function($q) {
                    return $q->select(['id', 'nickname']);
                },
                ])
            ->asArray()
            ->orderBy('co DESC')
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                }
            ])
            ->asArray()
            ->all();

        // Old pax, older tours if any
        $olderTours = [];
        $tourPaxIdList = [];
        foreach ($tourPax as $user) {
            $tourPaxIdList[] = $user['id'];
        }
        if (!empty($tourPaxIdList)) {
            $sql = 'SELECT t.id, t.code, t.name, t.status FROM at_tours t, at_ct p, at_booking_user bu, at_bookings b WHERE t.ct_id=p.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN ('.implode(',', $tourPaxIdList).') AND p.day_from<:start_date GROUP BY t.id LIMIT 10';
            $olderTours = Yii::$app->db->createCommand($sql, [':start_date'=>$theTour['day_from']])->queryAll();
        }

        // Tour hang
        $companyIdList = [];
        $tourAgents = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $companyIdList[] = $booking['case']['company_id'];
            }
        }
        if (!empty($companyIdList)) {
            $sql = 'SELECT id, name, image FROM at_companies WHERE id IN ('.implode(', ', $companyIdList).') LIMIT 1';
            $tourAgents = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Drivers and vehicles
        $sql = 'select * from at_tour_drivers where tour_id=:tour_id limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id'=>$productId])->queryAll();

        // Render view
        return $this->render('tour_r', [
            'theTour'=>$theTour,
            'thePeople'=>$thePeople,
            'inboxMails'=>$inboxMails,
            'theNotes'=>$theNotes,
            'theSysnotes'=>$theSysnotes,
            'tourPax'=>$tourPax,
            'tourRegInfo'=>$tourRegInfo,
            'tourRefs'=>$tourRefs,
            'olderTours'=>$olderTours,
            'tourAgents'=>$tourAgents,
            'tourDrivers'=>$tourDrivers,
            'tourGuides'=>$tourGuides,
            'tourOperators'=>$tourOperators,
            'tourCSStaff'=>$tourCSStaff,
            'tourFeedbacks'=>$tourFeedbacks,
        ]);
    }

    // Test
    public function actionX($id = 0)
    {
        $productId = Yii::$app->db->createCommand('SELECT ct_id FROM at_tours WHERE id=:id LIMIT 1', [':id'=>$id])->queryScalar();
        $theTour = Product::find()
            ->where(['id'=>$productId])
            ->with([
                'tournotes',
                'tourStats',
                'tournotes.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'bookings',
                'bookings.createdBy',
                'bookings.case',
                'bookings.case.owner'=>function($q) {
                    return $q->select(['id', 'nickname']);
                },
                'bookings.invoices',
                'bookings.payments',
                'days',
                'tour',
                'tour.operators',
                'tour.guides',
                'tour.tasks'=>function($q) {
                    return $q->orderBy('status, due_dt');
                },
                'tour.tasks.assignees'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour.tasks.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }

        // Amica all people
        $thePeople = Person::find()
            ->select(['id', 'name', 'fname', 'lname', 'nickname', 'email'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        // Tour guides
        // $sql = 'SELECT u.id, u.fname, u.lname, u.phone AS uphone, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:tour_id ORDER BY day LIMIT 100';
        // $tourGuidesOld = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql = 'select * from at_tour_guides where tour_id=:tour_id limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Tour operators
        $sql = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id=:tour_id';
        $tourOperators = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour guides
        $sql = 'select u.id, u.nickname from persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="cservice" AND tu.tour_id=:tour_id';
        $tourCSStaff = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['tour']['id']])->queryAll();

        // Tour feedbacks
        $sql = 'SELECT * FROM at_tour_feedbacks WHERE tour_id=:tour_id ORDER BY id DESC LIMIT 100';
        $tourFeedbacks = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Pax list
        $bookingIdList = [];
        $tourPax = [];
        foreach ($theTour['bookings'] as $booking) {
            $bookingIdList[] = $booking['id'];
        }
        if (!empty($bookingIdList)) {
            $sql = 'SELECT u.id, u.fname, u.lname, u.name, u.byear, u.bmonth, u.bday, u.gender, u.country_code, bu.booking_id FROM persons u, at_booking_user bu WHERE bu.user_id=u.id AND bu.status!="canceled" AND bu.booking_id IN ('.implode(',', $bookingIdList).')';
            $tourPax = Yii::$app->db->createCommand($sql)->queryAll();
            // Tour reg info
            //$sql = 'SELECT booking_id, reg_confirmed_dt FROM at_client_page_links WHERE reg_confirmed_dt!=0 AND booking_id IN ('.implode(',', $bookingIdList).')';
            $tourRegInfo = []; //Yii::$app->db->createCommand($sql)->queryAll();
        }

        // List of case id
        $caseIdList = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']) {
                $caseIdList[] = $booking['case']['id'];
            }
        }


        // Tour referrals
        $tourRefs = [];
        if (!empty($caseIdList)) {
            $sql = 'SELECT r.*, u.name FROM at_referrals r, persons u WHERE u.id=r.user_id AND r.case_id IN ('.implode(',', $caseIdList).') LIMIT 100';
            $tourRefs = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Post a note
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

            // $thePeople = $theTour['tour']['operators'];

            // Name mentions
            $toList = [];
            $toEmailList = [];
            $toIdList = [];
            if (isset($_POST['to']) && $_POST['to'] != '') {
                foreach ($thePeople as $person) {
                    $mention = '@['.$person['nickname'].']';
                    // 160107 Quick fix cho Ha beo khong email duoc Quynh Giang
                    $mentionEmail = strstr(str_replace('.', '', $person['email']), '@', true);
                    if (strpos($_POST['to'], $mention) !== false || strpos($_POST['to'], $mentionEmail) !== false) {
                        $toList[$person['id']] = $person;
                        $toEmailList[] = $person['email'];
                        $toIdList[] = $person['id'];
                    }
                }
                foreach ($thePeople as $person) {
                //TODO: foreach ($theCase['people'] as $person) {
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
            $theNote->rtype = 'tour';
            $theNote->rid = $theTour['tour']['id'];

            if (!$theNote->save(false)) {
                die('NOTE NOT SAVED');
            }


            if (!empty($toIdList)) {
                $nTo = [];
                foreach ($toIdList as $to) {
                    $nTo[] = [$theNote->id, $to];
                }
                Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
            }

            $relUrl = 'https://my.amicatravel.com/tours/r/'.$theTour['tour']['id'];
            $relName = $theTour['tour']['code'].' - '.$theTour['tour']['name'];

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
                                'rtype'=>'tour',
                                'rid'=>$theTour['tour']['id'],
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
                // Tour staff names, to include at the end of email subject
                $trail = $theTour['tour']['code'].' - '.$theTour['tour']['name'];
                if (count($theTour['bookings']) > 1) {
                    $trail .= ' (combined)';
                }

                $tourPaxCount = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourPaxCount[] = $booking['pax'];
                }
                $trail .= ' - '.implode('+', $tourPaxCount).'p ';
                $trail .= $theTour['day_count'].'d ';
                $trail .= date('j/n', strtotime($theTour['day_from'])).' - ';

                $tourStaff = [];
                foreach ($theTour['bookings'] as $booking) {
                    $tourStaff[] = $booking['createdBy']['nickname'];
                }
                foreach ($theTour['tour']['operators'] as $user) {
                    $tourStaff[] = $user['nickname'];
                }

                $trail .= implode(', ', $tourStaff);

                $subject = $theTour['tour']['code'].' - '.$title;
                $subject = str_replace($theTour['tour']['code'].' - '.$theTour['tour']['code'].' - ', $theTour['tour']['code'].' - ', $subject);
                if ($itag) {
                    $subject = '#important '.$subject;
                }
                if ($utag) {
                    $subject = '#urgent '.$subject;
                }
                if ($subject == '') {
                    $subject = 'No title';
                }
                $subject .= ' | Tour: '.$trail;

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    //['reply-to', Yii::$app->user->identity->email],
                    ['reply-to', 'msg-'.$theNote->id.'-'.USER_ID.'@amicatravel.com'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
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
        }

        $inboxMails = Mail::find()
            ->select(['id', 'from', 'to', 'cc', 'sent_dt', 'body', 'created_at', 'subject', 'attachment_count', 'files', 'updated_at', 'updated_by', 'tags', 'from_email'])
            ->where(['case_id'=>$caseIdList])
            ->andWhere(['or', 'LOCATE("[cs]", subject)!=0', ['like', 'tags', 'op']])
            ->asArray()
            ->all();

        $theNotes = Note::find()
            ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
            ->with([
                'files',
                'from'=>function($q) {
                    return $q->select(['id', 'nickname', 'image']);
                },
                'to'=>function($q) {
                    return $q->select(['id', 'nickname']);
                },
                ])
            ->asArray()
            ->orderBy('co DESC')
            ->all();

        $theSysnotes = Sysnote::find()
            ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
            ->with([
                'user'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'name']);
                }
            ])
            ->asArray()
            ->all();

        // Old pax, older tours if any
        $olderTours = [];
        $tourPaxIdList = [];
        foreach ($tourPax as $user) {
            $tourPaxIdList[] = $user['id'];
        }
        if (!empty($tourPaxIdList)) {
            $sql = 'SELECT t.id, t.code, t.name, t.status FROM at_tours t, at_ct p, at_booking_user bu, at_bookings b WHERE t.ct_id=p.id AND bu.booking_id=b.id AND b.product_id=p.id AND bu.user_id IN ('.implode(',', $tourPaxIdList).') AND p.day_from<:start_date GROUP BY t.id LIMIT 10';
            $olderTours = Yii::$app->db->createCommand($sql, [':start_date'=>$theTour['day_from']])->queryAll();
        }

        // Tour hang
        $companyIdList = [];
        $tourAgents = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $companyIdList[] = $booking['case']['company_id'];
            }
        }
        if (!empty($companyIdList)) {
            $sql = 'SELECT id, name, image FROM at_companies WHERE id IN ('.implode(', ', $companyIdList).') LIMIT 1';
            $tourAgents = Yii::$app->db->createCommand($sql)->queryAll();
        }

        // Drivers and vehicles
        $sql = 'select * from at_tour_drivers where tour_id=:tour_id limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id'=>$productId])->queryAll();

        // Render view
        return $this->render('tour_x', [
            'theTour'=>$theTour,
            'thePeople'=>$thePeople,
            'inboxMails'=>$inboxMails,
            'theNotes'=>$theNotes,
            'theSysnotes'=>$theSysnotes,
            'tourPax'=>$tourPax,
            'tourRegInfo'=>$tourRegInfo,
            'tourRefs'=>$tourRefs,
            'olderTours'=>$olderTours,
            'tourAgents'=>$tourAgents,
            'tourDrivers'=>$tourDrivers,
            'tourGuides'=>$tourGuides,
            'tourOperators'=>$tourOperators,
            'tourCSStaff'=>$tourCSStaff,
            'tourFeedbacks'=>$tourFeedbacks,
        ]);
    }

    public function actionTongHopRoiNuoc($month = '')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND LOCATE(:rn, dvtour_name)!=0 GROUP BY ym ORDER BY ym DESC', ['rn'=>'ối nước'])
            ->queryAll();
        $theCptx = Cpt::find()
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.guides'=>function($q) {
                    return $q->select(['id', 'name', 'phone']);
                },
                // 'tour.operators'=>function($q) {
                //     return $q->select(['id', 'name', 'phone']);
                // },
            ])
            ->where('LOCATE(:rn, dvtour_name)!=0', [':rn'=>'ối nước'])
            ->andWhere('SUBSTRING(dvtour_day,1,7)=:ym', [':ym'=>$month])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();
        // Operators
        $tourIdList = [0];
        foreach ($theCptx as $cpt) {
            $tourIdList[] = $cpt['tour']['id'];
        }
        $sql = 'SELECT u.nickname AS name, u.phone, tu.tour_id FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.role="operator" AND tu.tour_id IN ('.implode(',', $tourIdList).')';
        $tourOperators = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('tours_tong-hop-roi-nuoc', [
            'theCptx'=>$theCptx,
            'month'=>$month,
            'monthList'=>$monthList,
            'tourOperators'=>$tourOperators,
        ]);
    }

    public function actionTongHopNuocUong($month = '', $name = '') {
        $getMonth = $month;

        if (strlen($getMonth) != 7) {
            $getMonth = date('Y-m');
        }

        $monthList = Yii::$app->db
            //->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND LOCATE(:rn, dvtour_name)!=0 AND price=3190 GROUP BY ym ORDER BY ym DESC', ['rn'=>'ước uống'])
            ->createCommand('SELECT SUBSTRING(dvtour_day,1,7) AS ym, COUNT(*) AS total FROM cpt WHERE plusminus = "plus" AND price=3190 GROUP BY ym ORDER BY ym DESC', ['rn'=>'ước uống'])
            ->queryAll();
        $theCptx = Cpt::find()
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'tour.guides'=>function($q) {
                    return $q->select(['id', 'name', 'phone']);
                },
                'tour.operators'=>function($q) {
                    return $q->select(['id', 'name', 'phone']);//->onCondition('at_tour_user.role="op"');
                },
            ])
            //->where('LOCATE(:rn, dvtour_name)!=0', [':rn'=>'ước uống'])
            ->andWhere('price=3190', [':ym'=>$getMonth])
            ->andWhere('SUBSTRING(dvtour_day,1,7)=:ym', [':ym'=>$getMonth])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        return $this->render('tours_tong-hop-nuoc-uong', [
            'theCptx'=>$theCptx,
            'getMonth'=>$getMonth,
            'name'=>$name,
            'monthList'=>$monthList,
        ]);
    }

    public function actionU($id = 0, $for = '') {
        $theTourOld = Tour::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($theTourOld['status'] == 'draft') {
            return $this->redirect('@web/tours/accept/'.$theTourOld['id']);
        }

        $theTour = Product::find()
            ->where(['id'=>$theTourOld['ct_id']])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array($for, ['', 'dhsg', 'ducanh'])) {
            $for = '';
        }

        if (!in_array(USER_ID, [1, 118, 25457, 8162])) {
            throw new HttpException(403, 'Access denied.');
        }

        if (USER_ID == 25457 && $for != 'dhsg') {
            throw new HttpException(403, 'Access denied.');
        }

        if (USER_ID == 8162 && $for != 'ducanh') {
            // throw new HttpException(403, 'Access denied.');
        }

        if ($for == 'dhsg') {
            $sql = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND u.id IN (25457, 27726, 37675) ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        } elseif ($for == 'ducanh') {
            $sql = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND u.id IN (8162, 34596) ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $sql = 'select ur.*, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u, at_user_role ur WHERE u.is_member="yes" AND u.status="on" AND ur.user_id=u.id AND ur.role_id=5 AND u.status="on" ORDER BY u.lname';
            $operatorList = Yii::$app->db->createCommand($sql)->queryAll();
        }

        $sql = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
        $oldOperatorList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

        $theForm = new TourAcceptForm;
        $theForm['op_code'] = $theTour['op_code'];
        $theForm['op_name'] = $theTour['op_name'];
        $theForm['owner'] = $theTourOld['owner'];

        $oldOperatorIdList = [];
        foreach ($oldOperatorList as $operator) {
            $oldOperatorIdList[] = $operator['user_id'];
        }

        $theForm['operators'] = $oldOperatorIdList;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {

            if ($for == '') {
                $theTour['op_name'] = $theForm['op_name'];
                $theTour['op_code'] = $theForm['op_code'];
                $theTour['client_ref'] = $theForm['client_ref'];
                $theTour->save(false);
            }
            Yii::$app->db->createCommand()->update(
                'at_tours', [
                    'uo'=>NOW,
                    'ub'=>USER_ID,
                    'name'=>$theTour['op_name'],
                    'code'=>$theTour['op_code'],
                    'owner'=>$theForm['owner'],
                ], ['id'=>$theTourOld['id']])->execute();

            Yii::$app->db->createCommand()->update('at_search', [
                'search'=>str_replace(['-'], [''], \fURL::makeFriendly($theTour['op_code'].' '.$theTour['op_name'], '-')),
                'found'=>$theTour['op_code'].' '.$theTour['op_name'],
                ], [
                'rtype'=>'tour',
                'rid'=>$theTourOld['id'],
                ])->execute();
    
            // Delete removed ops
            foreach ($oldOperatorIdList as $id) {
                if ($for == 'dhsg' && !in_array($id, $theForm['operators']) && in_array($id, [25457, 27726, 37675])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id'=>$theTourOld['id'], 'user_id'=>$id, 'role'=>'operator'])
                        ->execute();
                }
                if ($for == 'ducanh' && !in_array($id, $theForm['operators']) && in_array($id, [8162, 34596])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id'=>$theTourOld['id'], 'user_id'=>$id, 'role'=>'operator'])
                        ->execute();
                }
                if ($for == '' && !in_array($id, $theForm['operators'])) {
                    Yii::$app->db->createCommand()
                        ->delete('at_tour_user', ['tour_id'=>$theTourOld['id'], 'user_id'=>$id, 'role'=>'operator'])
                        ->execute();
                }
            }

            // Save and email new ops
            foreach ($theForm['operators'] as $userId) {
                if (!in_array($userId, $oldOperatorIdList)) {
                    // Save tour op
                    Yii::$app->db->createCommand()->insert('at_tour_user', [
                        'tour_id'=>$theTourOld['id'],
                        'user_id'=>$userId,
                        'role'=>'operator',
                        ])->execute();

                    // Email
                    if ($for == 'dhsg') {
                        $this->mgIt(
                            'ims | Kim Ngoc has just assigned an operator to tour "'.$theTour['op_code'].' - '.$theTour['op_name'],
                            '//mg/tour_assign',
                            [
                                'theTour'=>$theTour,
                                'theTourOld'=>$theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', 'bich.ngoc@amica-travel.com', 'Ngoc', 'HB.'],
                            ]
                        );
                        $newOperator = Person::find(['id', 'fname', 'lname', 'email'])->where(['id'=>$userId])->asArray()->one();
                        $this->mgIt(
                            'ims | Tour "'.$theTour['op_code'].' - '.$theTour['op_name'].'" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'=>$theTour,
                                'theTourOld'=>$theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $newOperator['email'], $newOperator['lname'], $newOperator['fname']],
                            ]
                        );
                    }

                    if ($for == 'ducanh') {
                        $this->mgIt(
                            'ims | Đức Anh has just assigned an operator to tour "'.$theTour['op_code'].' - '.$theTour['op_name'],
                            '//mg/tour_assign',
                            [
                                'theTour'=>$theTour,
                                'theTourOld'=>$theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', 'bich.ngoc@amica-travel.com', 'Ngoc', 'HB.'],
                            ]
                        );
                        $newOperator = Person::find(['id', 'fname', 'lname', 'email'])->where(['id'=>$userId])->asArray()->one();
                        $this->mgIt(
                            'ims | Tour "'.$theTour['op_code'].' - '.$theTour['op_name'].'" has been assigned to you',
                            '//mg/tour_assign',
                            [
                                'theTour'=>$theTour,
                                'theTourOld'=>$theTourOld,
                            ],
                            [
                                ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                ['to', $newOperator['email'], $newOperator['lname'], $newOperator['fname']],
                            ]
                        );
                    }

                }
            }

            return $this->redirect('@web/tours/r/'.$theTourOld['id']);
        }

        return $this->render('tours_accept', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'operatorList'=>$operatorList,
            'for'=>$for,
        ]);
    }

    // Assign customer care personnel
    public function actionCskh($id = 0) {
        $theTour = Product::find()
            ->where(['id'=>$id, 'op_status'=>'op', 'op_finish'=>''])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array(USER_ID, [1, 27388, 29296])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $sql = 'select u.id, u.fname, u.lname, u.email, CONCAT(u.name, " ", u.email) AS name from persons u WHERE id IN (1351, 12952, 29296, 30554, 33415) AND u.status="on" AND u.is_member="yes" ORDER BY lname, fname';
        $cssList = Yii::$app->db->createCommand($sql)->queryAll();

        $theForm = new TourAssignCsForm;

        $sql = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="cservice"';
        $oldCssList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();
        
        $oldCssIdList = [];
        foreach ($oldCssList as $cs) {
            $oldCssIdList[] = $cs['user_id'];
        }
        $theForm['css'] = $oldCssIdList;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            // Remove old
            foreach ($oldCssIdList as $oldId) {
                if (!in_array($oldId, $theForm['css'])) {
                    Yii::$app->db->createCommand()->delete('at_tour_user', ['tour_id'=>$theTourOld['id'], 'user_id'=>$oldId, 'role'=>'cservice'])->execute();
                }
            }
            // Email new
            foreach ($theForm['css'] as $newId) {
                if (!in_array($newId, $oldCssIdList)) {
                    // Save tour op
                    Yii::$app->db->createCommand()->insert('at_tour_user', [
                        'tour_id'=>$theTourOld['id'],
                        'user_id'=>$newId,
                        'role'=>'cservice',
                        ])->execute();
                    // Email
                    foreach ($cssList as $user) {
                        if ($user['id'] == $newId && $newId != USER_ID) {
                            $this->mgIt(
                                'ims | Tour "'.$theTour['op_code'].' - '.$theTour['op_name'].'" has been assigned to you',
                                '//mg/tour_assign',
                                [
                                    'theTour'=>$theTour,
                                    'theTourOld'=>$theTourOld,
                                ],
                                [
                                    ['from', 'noreply-ims@amicatravel.com', 'Amica Travel', 'IMS'],
                                    ['to', $user['email'], $user['lname'], $user['fname']],
                                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                                ]
                            );
                        }
                    }
                }
            }
            // Redir
            return $this->redirect('@web/tours/r/'.$theTourOld['id']);
        }

        return $this->render('tours_cskh', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'cssList'=>$cssList,
        ]);
    }

    // Cancel a tour; must cancel bookings first
    public function actionCxl($id = 0) {
        $theTour = Product::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        if (!in_array(USER_ID, [1, 118, 8162])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // TODO check if all bookings have been canceled

        $sql = 'select user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
        $oldOperatorList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

        $oldOperatorIdList = [];
        foreach ($oldOperatorList as $operator) {
            $oldOperatorIdList[] = $operator['user_id'];
        }

        if (Yii::$app->request->post('confirm') == 'cancel') {
            // Cancel tour
            Yii::$app->db->createCommand()->update('at_tours', ['status'=>'deleted'], ['id'=>$theTourOld['id']])->execute();
            Yii::$app->db->createCommand()->update('at_ct', ['op_finish'=>'canceled', 'op_finish_dt'=>NOW], ['id'=>$theTour['id']])->execute();
            // Email people
            // Set message
            Yii::$app->session->setFlash('success', 'Tour operation has been canceled: '.$theTour['op_code']);
            // Redir
            return $this->redirect('@web/tours/r/'.$theTourOld['id']);
        }

        return $this->render('tours_cxl', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            //'operatorList'=>$operatorList,
        ]);
    }

    public function actionAjax2($search = 'hansgn') {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        if (isset($_POST['action'], $_POST['dvtour_id'], $_POST['tour_id'], $_POST['formdata'])) {
            $theTourOld = Tour::find()
                ->where(['id'=>$_POST['tour_id']])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found');
            }
            $theTourOld = Tour::find()
                ->where(['id'=>$_POST['tour_id']])
                ->asArray()
                ->one();

            if ($_POST['dvtour_id'] != 0) {
                $theCpt = Cpt::find()
                    ->where(['dvtour_id'=>$_POST['dvtour_id']])
                    ->asArray()
                    ->one();
                if (!$theCpt) {
                    throw new HttpException(404, 'Tour cost not found');
                }

                $checkStatus = [
                    'c1'=>strpos($dv['c1'], 'on') !== false,
                    'c2'=>strpos($dv['c2'], 'on') !== false,
                    'c3'=>strpos($dv['c3'], 'on') !== false,
                    'c4'=>strpos($dv['c4'], 'on') !== false,
                    'c5'=>strpos($dv['c5'], 'on') !== false,
                    'c6'=>strpos($dv['c6'], 'on') !== false,
                    'c7'=>strpos($dv['c7'], 'on') !== false,
                    'c8'=>strpos($dv['c8'], 'on') !== false,
                    'c9'=>strpos($dv['c9'], 'on') !== false,
                ];

            }

            foreach ($_POST['formdata'] as $fd) {
                $_POST[$fd['name']] = $fd['value'];
            }


            // Action create
            if ($_POST['action'] == 'create') {
                if (!in_array(USER_ID, $tourOperatorIds) && USER_ID != 1) {
                    die(json_encode(array('NOK', '2 - Access denied for tour : ['.$_POST['tour_id'].']')));
                }
                $fv = new hxFormValidation();
                $_POST['qty'] = str_replace(',', '', $_POST['qty']);
                $_POST['price'] = str_replace(',', '', $_POST['price']);
                $fv->setRules('dvtour_name', 'Tên dịch vụ', 'trim|required|max_length[64]');
                $fv->setRules('oppr', 'Đối tác / Cung cấp', 'trim|max_length[64]');
                $fv->setRules('venue_id', 'Chọn địa điểm (venue)', 'trim|required|is_natural');
                $fv->setRules('qty', 'Số lượng', 'trim|required|is_numeric');
                $fv->setRules('unit', 'Đơn vị', 'trim|required|max_length[64]');
                $fv->setRules('price', 'Đơn giá', 'trim|required|is_numeric');
                $fv->setRules('unitc', 'Đơn vị tiền tệ', 'trim|required|exact_length[3]');
                $fv->setRules('vat', 'VAT (%)', 'trim|required|is_natural');
                $fv->setRules('prebooking', 'Cần book trước hay không', 'trim|required');
                $fv->setRules('payer', 'Người trả', 'trim|required|max_length[64]');
                $fv->setRules('due', 'Hạn thanh toán', 'trim|exact_length[10]');
                $fv->setRules('status', 'Tình trạng đặt trước', 'trim|required|exact_length[1]');
                if ($fv->run()) {
                    $q = $db->query('INSERT INTO cpt (created_at, created_by, updated_at, updated_by, tour_id, dvtour_day, dvtour_name, oppr,
                            adminby, via_company_id, by_company_id, venue_id, start, number, qty, unit, price, unitc, vat, prebooking, payer, status, due, plusminus)
                            VALUES (%s, %i, %s, %i, %i, %s, %s, %s, %s, %i, %i, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', 
                            NOW, USER_ID, NOW, USER_ID, $_POST['tour_id'], $_POST['dvtour_day'], $_POST['dvtour_name'], $_POST['oppr'],
                            $_POST['adminby'], $_POST['via_company_id'], $_POST['by_company_id'], $_POST['venue_id'], $_POST['start'], $_POST['number'],
                            $_POST['qty'], $_POST['unit'], $_POST['price'], $_POST['unitc'], $_POST['vat'], $_POST['prebooking'], $_POST['payer'], $_POST['status'], $_POST['due'], $_POST['plusminus']
                        );
                    $newDvId = $q->getAutoIncrementedValue();

                    // Save note if any
                    if ($_POST['mm'] != '') {
                        $db->query('INSERT INTO at_comments (created_at, created_by, updated_at, updated_by, status, rtype, rid, pid, body) VALUES (%s, %i, %s, %i, %s, %s, %i, %i, %s)',
                            NOW, USER_ID, NOW, USER_ID, 'on', 'cpt', $newDvId, $_POST['tour_id'], $_POST['mm']
                        );
                    }
                
                    die(json_encode(array('OK-CREATE', '', $newDvId, $_POST['dvtour_day'])));
                } else {
                    die(json_encode(array('NOK',strip_tags($fv->getErrorMessage()))));
                }
            }
        }
    }


    public function actionCalendar($date = '', $view = '')
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

        $sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this ORDER BY day_from, id LIMIT 1000';
        $theTours = Product::findBySql($sql, [':this'=>$thisWeek, ':next'=>$nextWeek])
            ->with([
                'tournotes',
                'tournotes.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'tour'=>function($q) {
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'=>function($q) {
                    return $q->select(['dvtour_id', 'tour_id', 'dvtour_name', 'dvtour_day', 'venue_id', 'qty', 'unit'])
                        ->where('venue_id!=0')
                        ->andWhere(['or', 'dvtour_name="Khách sạn"', 'dvtour_name="Hotel"', 'dvtour_name="Tàu ngủ đêm"', 'dvtour_name="Tàu Hạ Long"', 'dvtour_name="nhà dân"', 'dvtour_name="Accommodation"']);
                },
                'tour.cpt.venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'tour.operators'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()->all();

        // Drivers and vehicles
        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }

        // Khach sinh nhat trong khoang nay
        $dayList = [];
        for ($i = 0; $i < 7; $i ++) {
            $dayList[] = date('j/n', strtotime('+'.$i.' days', strtotime($thisWeek)));
        }
        $sql = 'SELECT u.id AS user_id, u.bday, u.bmonth, u.byear, u.name, p.id AS product_id FROM persons u, at_booking_user bu, at_bookings b, at_ct p WHERE u.id=bu.user_id AND b.id=bu.booking_id AND p.id=b.product_id AND CONCAT(u.bday, "/", u.bmonth) IN ("'.implode('","', $dayList).'") AND p.id IN ('.implode(',', $tourIdList).')';
        $paxWithBirthdays = Yii::$app->db->createCommand($sql, [':day1'=>date('j', strtotime($thisWeek)), ':day2'=>date('j', strtotime($nextWeek)), ':month1'=>date('n', strtotime($thisWeek)), ':month2'=>date('n', strtotime($nextWeek))])->queryAll();
        if (isset($_GET['x'])) {
            \fCore::expose($paxWithBirthdays);
            exit;
        }

        $sql = 'select *, IF(guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id IN ('.implode(',', $tourIdList).') order by use_from_dt limit 1000';
        $tourGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'select *, IF(driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id IN ('.implode(',', $tourIdList).') order by use_from_dt limit 1000';
        $tourDrivers = Yii::$app->db->createCommand($sql)->queryAll();


        if ($view == 'v') {
            return $this->render('tours_calendar_v', [
                'theTours'=>$theTours,
                'theGuides'=>[],
                'prevWeek'=>$prevWeek,
                'thisWeek'=>$thisWeek,
                'nextWeek'=>$nextWeek,
                'tourDrivers'=>$tourDrivers,
                'tourGuides'=>$tourGuides,
                'paxWithBirthdays'=>$paxWithBirthdays,
            ]);
        }

        return $this->render('tours_calendar', [
            'theTours'=>$theTours,
            'prevWeek'=>$prevWeek,
            'thisWeek'=>$thisWeek,
            'nextWeek'=>$nextWeek,
            'tourDrivers'=>$tourDrivers,
            'tourGuides'=>$tourGuides,
            'paxWithBirthdays'=>$paxWithBirthdays,
        ]);
    }

    // Month calendar
    public function actionCalendarMonth($date = '', $view = '')
    {
        if (strlen($date) != 10) {
            $date = date('Y-m-d');
        }

        $thisMonth = $date;
        $prevMonth = date('Y-m-d', strtotime('-30 days', strtotime($thisMonth)));
        $nextMonth = date('Y-m-d', strtotime('+30 days', strtotime($thisMonth)));

        $sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this ORDER BY day_from, id LIMIT 1000';
        $theTours = Product::findBySql($sql, [':this'=>$thisMonth, ':next'=>$nextMonth])
            ->with([
                'tournotes',
                'tournotes.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'meals', 'rid']);
                },
                'bookings.createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'tour'=>function($q) {
                    return $q->select(['id', 'ct_id']);
                },
                'tour.cpt'=>function($q) {
                    return $q->select(['dvtour_id', 'tour_id', 'dvtour_name', 'dvtour_day', 'venue_id', 'qty', 'unit'])
                        ->where('venue_id!=0')
                        ->andWhere(['or', 'dvtour_name="Khách sạn"', 'dvtour_name="Hotel"', 'dvtour_name="Tàu ngủ đêm"', 'dvtour_name="nhà dân"', 'dvtour_name="Accommodation"']);
                },
                'tour.cpt.venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'tour.operators'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()->all();

        // Drivers and vehicles
        $tourIdList = [0];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
        }

        $sql = 'select *, IF(guide_user_id=0, guide_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id IN ('.implode(',', $tourIdList).') order by use_from_dt limit 1000';
        $tourGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'select *, IF(driver_user_id=0, driver_name, (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id IN ('.implode(',', $tourIdList).') order by use_from_dt limit 1000';
        $tourDrivers = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('tours_calendar-month', [
            'theTours'=>$theTours,
            'prevMonth'=>$prevMonth,
            'thisMonth'=>$thisMonth,
            'nextMonth'=>$nextMonth,
            'tourDrivers'=>$tourDrivers,
            'tourGuides'=>$tourGuides,
        ]);
    }

    // Vehicles and drivers settings
    // id = product id
    // action = add|addtime|edit|delete = add more svc time
    // edit = edit
    // delete = delete
    public function actionDrivers($id = 0, $action = 'add', $item_id = 0)
    {
        $theTour = Product::find()
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->with(['days'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        if (!in_array(USER_ID, [1])) {
            // throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Drivers and vehicles
        $sql = 'select *, driver_name, driver_user_id, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $tourDrivers = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Driver list
        $sql = 'select u.id, CONCAT(u.name, " - ", REPLACE(u.phone, " ", "")) AS namephone from persons u, at_profiles_driver p where u.id=p.user_id order by u.lname, u.fname limit 3000';
        $theDrivers = Yii::$app->db->createCommand($sql)->queryAll();

        $theDriver = false;


        // Check action
        if (
            !in_array($action, ['add', 'addtime', 'edit', 'delete'])
            || (in_array($action, ['addtime', 'edit', 'delete']) && $item_id == 0)
        ) {
            return $this->redirect(DIR.URI);
        }


        // action add
        if ($action == 'add') {
            $theForm = new TourDriverForm;
            $theForm->bookingStatus = 'confirmed';
            $theForm->useTimezone = 'Asia/Ho_Chi_Minh';
            $theForm->useFromDt = $theTour['day_from'].' 08:00'; 
            $theForm->useUntilDt = date('Y-m-d', strtotime('+ '.($theTour['day_count'] - 1).' days', strtotime($theTour['day_from']))).' 22:00';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $driverUserId = 0;
                foreach ($theDrivers as $driver) {
                    if ($theForm['driverName'] == trim($driver['namephone'])) {
                        $driverUserId = $driver['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->insert('at_tour_drivers', [
                    'created_dt'=>NOW,
                    'created_by'=>USER_ID,
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'tour_id'=>$theTour['id'],
                    'vehicle_type'=>$theForm['vehicleType'],
                    'vehicle_number'=>$theForm['vehicleNumber'],
                    'driver_company'=>$theForm['driverCompany'],
                    'driver_name'=>$theForm['driverName'],
                    'driver_user_id'=>$driverUserId,
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ])->execute();

                return $this->redirect(DIR.URI);
            }
        }

        // action add time
        if ($action == 'addtime' && $item_id != 0) {
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            if (!in_array(USER_ID, [1, 118, 29296, 33415, $theDriver['created_by'], $theDriver['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourDriverForm;

            $theForm->useTimezone = $theDriver['use_timezone'];
            $theForm->vehicleType = $theDriver['vehicle_type'];
            $theForm->vehicleNumber = $theDriver['vehicle_number'];
            $theForm->driverCompany = $theDriver['driver_company'];
            $theForm->driverName = $theDriver['driver_name'];
            $theForm->useFromDt = $theDriver['use_from_dt'];
            $theForm->useUntilDt = $theDriver['use_until_dt'];
            $theForm->bookingStatus = $theDriver['booking_status'];
            $theForm->points = $theDriver['points'];
            $theForm->note = $theDriver['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->insert('at_tour_drivers', [
                    'created_dt'=>NOW,
                    'created_by'=>USER_ID,
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'parent_id'=>$item_id,
                    'tour_id'=>$theTour['id'],
                    'vehicle_type'=>$theForm['vehicleType'],
                    'vehicle_number'=>$theForm['vehicleNumber'],
                    'driver_company'=>$theForm['driverCompany'],
                    'driver_name'=>$theForm['driverName'],
                    'driver_user_id'=>$theDriver['driver_user_id'],
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    //'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ])->execute();

                return $this->redirect(DIR.URI);
            }
        }

        // action edit
        if ($action == 'edit' && $item_id != 0) {
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            $allowEditList = [1, 118, 29296, 33415, $theDriver['created_by'], $theDriver['updated_by']];

            // Tour ops
            $sql = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);

            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourDriverForm;

            $theForm->useTimezone = $theDriver['use_timezone'];
            $theForm->vehicleType = $theDriver['vehicle_type'];
            $theForm->vehicleNumber = $theDriver['vehicle_number'];
            $theForm->driverCompany = $theDriver['driver_company'];
            $theForm->driverName = $theDriver['driver_name'];
            $theForm->useFromDt = $theDriver['use_from_dt'];
            $theForm->useUntilDt = $theDriver['use_until_dt'];
            $theForm->bookingStatus = $theDriver['booking_status'];
            $theForm->points = $theDriver['points'];
            $theForm->note = $theDriver['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $driverUserId = 0;
                foreach ($theDrivers as $driver) {
                    if ($theForm['driverName'] == trim($driver['namephone'])) {
                        $driverUserId = $driver['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->update('at_tour_drivers', [
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'vehicle_type'=>$theForm['vehicleType'],
                    'vehicle_number'=>$theForm['vehicleNumber'],
                    'driver_company'=>$theForm['driverCompany'],
                    'driver_name'=>$theDriver['driver_user_id'] != 0 ? $theDriver['driver_name'] : $theForm['driverName'],
                    'driver_user_id'=>$theDriver['driver_user_id'] != 0 ? $theDriver['driver_user_id'] : $driverUserId,
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ], ['id'=>$item_id])->execute();
                Yii::$app->session->setFlash('success', 'Driver info has been updated: '.$theDriver['driver_company'].' / '.$theDriver['driver_name']);
                return $this->redirect(DIR.URI);
            }
        }

        // action delete
        if ($action == 'delete' && $item_id != 0) {
            $theForm = false;
            foreach ($tourDrivers as $driver) {
                if ($driver['id'] == $item_id) {
                    $theDriver = $driver;
                }
            }

            if (!$theDriver) {
                throw new HttpException(404, 'Driver info not found');
            }

            if (!in_array(USER_ID, [1, 118, $theDriver['created_by'], $theDriver['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            //if (Yii::$app->request->post('confirm') == 'delete') {
                Yii::$app->db->createCommand()->delete('at_tour_drivers', ['parent_id'=>$item_id])->execute();
                Yii::$app->db->createCommand()->delete('at_tour_drivers', ['id'=>$item_id])->execute();
                Yii::$app->session->setFlash('success', 'Driver info has been deleted: '.$theDriver['driver_company'].' / '.$theDriver['driver_name']);
                return $this->redirect(DIR.URI);
            //}
        }
    
        return $this->render('tours_drivers', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'tourDrivers'=>$tourDrivers,
            'theDriver'=>$theDriver,
            'theDrivers'=>$theDrivers,
            'action'=>$action,
            'item_id'=>$item_id,
        ]);
    }

    // Pax list
    public function actionPax($id, $action = 'list', $pax = 0, $booking = 0, $contact = 0)
    {
        $theTour = Product::find()
            ->with([
                'pax'=>function($q){
                    return $q->orderBy('booking_id, name');
                },
                'bookings',
                'bookings.case',
                'bookings.case.people',
                'bookings.people',
                'bookings.people.country',
            ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $bookingIdList = [];
        foreach ($theTour['bookings'] as $_booking) {
            $bookingIdList[] = $_booking['id'];
        }

        // If only 1 booking
        if (count($bookingIdList) == 1) {
            $booking = $bookingIdList[0];
        }

        $allowList = [1, 8162, 34595, 39748, 1351, 29296, 12952, 27388, 35071, 33415];

        if ($action == 'link' && $contact != 0) {
            // Link new pax to existing contact
            foreach ($theTour['bookings'] as $tbooking) {
                foreach ($tbooking['case']['people'] as $bkcontact) {
                    if ($bkcontact['id'] == $contact) {
                        $thePax = new Pax;
                        $thePax->account_id = 1;
                        $thePax->created_by = USER_ID;
                        $thePax->updated_by = USER_ID;
                        $thePax->created_dt = NOW;
                        $thePax->updated_dt = NOW;
                        $thePax->tour_id = $theTour['id'];
                        $thePax->booking_id = $booking;
                        $thePax->contact_id = $bkcontact['id'];
                        $thePax->name = $bkcontact['name'];
                        $thePax->pp_country_code = $bkcontact['country_code'];
                        $data = [
                            'pp_country_code'=>$bkcontact['country_code'],
                            'gender'=>$bkcontact['gender'],
                            'tel'=>$bkcontact['phone'],
                            'email'=>$bkcontact['email'],
                            'name'=>$bkcontact['name'],
                        ];
                        $thePax->data = serialize($data);
                        $thePax->save(false);
                        break;
                    }
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been linked to contact.'));
            return $this->redirect('');
        }

        if ($action == 'edit' && $pax != 0) {
            $theForm = new \app\models\BookingPaxForm;
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $theForm->setAttributes(unserialize($thePax->data));
        } elseif ($action == 'cancel' && $pax != 0) {
            // TODO cancel pax booking
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->status = 'canceled';
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been canceled.'));
            return $this->redirect('/tours/pax/'.$theTour['id']);

        } elseif ($action == 'uncancel' && $pax != 0) {
            // TODO cancel pax booking
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->status = '';
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax has been un-canceled.'));
            return $this->redirect('/tours/pax/'.$theTour['id']);
        } elseif ($action == 'delete' && $pax != 0) {
            // TODO delete pax info
            $thePax = Pax::findOne($pax);
            if (!$thePax) {
                throw new HttpException(404, 'Pax not found.');
            }
            if (!in_array($thePax->booking_id, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if (!in_array(USER_ID, [$thePax['created_by'], $thePax['updated_by']])) {
                throw new HttpException(404, 'Access denied.');
            }
            $thePax->delete();
            Yii::$app->session->setFlash('success', Yii::t('tour_pax', 'Pax info has been deleted.'));
            return $this->redirect('/tours/pax/'.$theTour['id']);
        } else {
            // List all pax and add new pax
            $theForm = new \app\models\BookingPaxForm;
            $thePax = new Pax;
            $thePax->account_id = 1;
            $thePax->created_by = USER_ID;
            $thePax->updated_by = USER_ID;
            $thePax->created_dt = NOW;
            $thePax->updated_dt = NOW;
            $thePax->tour_id = $theTour['id'];
            $thePax->booking_id = $booking;
            $thePax->contact_id = 0;
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if ($action == 'list' && !in_array($booking, $bookingIdList)) {
                throw new HttpException(404, 'Invalid booking.');
            }
            if ($action == 'list' && !in_array(USER_ID, $allowList)) {
                throw new HttpException(404, 'Access denied.');
            }

            $thePax->is_repeating = $theForm->is_repeating;
            $thePax->name = $theForm->name;
            $thePax->pp_name = $theForm->pp_name;
            $thePax->pp_name2 = $theForm->pp_name2;
            $thePax->pp_gender = $theForm->pp_gender;
            $thePax->pp_country_code = $theForm->pp_country_code == '' ? null : $theForm->pp_country_code;
            $thePax->pp_number = $theForm->pp_number;
            $thePax->pp_idate = $theForm->pp_iyear.'-'.$theForm->pp_imonth.'-'.$theForm->pp_iday;
            $thePax->pp_edate = $theForm->pp_eyear.'-'.$theForm->pp_emonth.'-'.$theForm->pp_eday;
            $thePax->pp_birthdate = $theForm->pp_byear.'-'.$theForm->pp_bmonth.'-'.$theForm->pp_bday;
            $thePax->data = serialize($theForm->getAttributes());
            $thePax->save(false);
            Yii::$app->session->setFlash('success', Yii::t('c', 'Pax info has been saved.'));
            return $this->redirect('/tours/pax/'.$theTour['id']);
        }

        // $inboxMails = Mail::find()
        //     ->select(['id', 'files'])
        //     ->where(['case_id'=>$caseIdList])
        //     ->asArray()
        //     ->all();

        // $theNotes = Note::find()
        //     ->select(['id'])
        //     ->where(['rtype'=>'tour', 'rid'=>$theTour['tour']['id']])
        //     ->with([
        //         'files',
        //     ->asArray()
        //     ->all();

        $countryList = \common\models\Country::find()
            ->select(['name_en', 'name_vi', 'code'])
            ->asArray()
            ->all();

        $genderList = [
            'male'=>'Male',
            'female'=>'Female',
        ];

        return $this->render('tour_pax', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'thePax'=>$thePax,
            'countryList'=>$countryList,
            'genderList'=>$genderList,
        ]);
    }

    // Copy chi phi tour
    public function actionCopyCosts($s='', $d='')
    {
        $sourceTour = Tour::find()
            ->where(['code'=>$s])
            ->with(['cpt', 'cpt.venue'])
            ->asArray()
            ->one();
        $destTour = Tour::find()
            ->where(['code'=>$d])
            ->with(['cpt', 'cpt.venue'])
            ->asArray()
            ->one();
        if (!$sourceTour && !$destTour) {
            return $this->render('tours_copy-costs');
        }
        $sourceProduct = Product::find()
            ->where(['op_code'=>$s])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$sourceProduct) {
            throw new HttpException(404, 'Source tour not found.');         
        }
        $destProduct = Product::find()
            ->where(['op_code'=>$d])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$destProduct) {
            throw new HttpException(404, 'Destination tour not found.');            
        }

        if (isset($_GET['sd']) && isset($_GET['dd']) && strlen($_GET['sd']) == 10 && strlen($_GET['dd']) == 10) {
            $dc = (int)$_GET['dc'] == 0 ? 1 : (int)$_GET['dc'];
            $startDate = date('Y-m-d', strtotime($_GET['sd']));
            $dayList = [$startDate];
            for ($i = 1; $i < $dc; $i ++) {
                $dayList[] = date('Y-m-d', strtotime('+ '.($i).' days', strtotime($startDate)));
            }

            if (USER_ID == 1) {
                \fCore::expose($dayList);
                exit;
            }
            
            $sourceCpt = Cpt::find()
                ->where(['tour_id'=>$sourceTour['id'], 'dvtour_day'=>$dayList])
                ->asArray()
                ->all();
            if (!empty($sourceCpt)) {
                foreach ($sourceCpt as $cpt) {
                    Yii::$app->db
                        ->createCommand()
                        ->insert('cpt', [
                        'created_at'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_at'=>NOW,
                        'updated_by'=>USER_ID,
                        'tour_id'=>$destTour['id'],
                        'dvtour_day'=>date('Y-m-d', strtotime($_GET['dd'])),
                        'dvtour_name'=>$cpt['dvtour_name'],
                        'oppr'=>$cpt['oppr'],
                        'venue_id'=>$cpt['venue_id'],
                        'by_company_id'=>$cpt['by_company_id'],
                        'via_company_id'=>$cpt['via_company_id'],
                        'qty'=>$cpt['qty'],
                        'unit'=>$cpt['unit'],
                        'price'=>$cpt['price'],
                        'unitc'=>$cpt['unitc'],
                        'booker'=>$cpt['booker'],
                        'payer'=>$cpt['payer'],
                        'adminby'=>$cpt['adminby'],
                        'status'=>'n',
                        'plusminus'=>$cpt['plusminus'],
                    ])->execute();
                    echo $cpt['dvtour_name'];
                }
            }
            return $this->redirect('@web/tours/copy-costs?s='.$s.'&d='.$d);
        }

        return $this->render('tours_copy-costs', [
            'sourceProduct'=>$sourceProduct,
            'destProduct'=>$destProduct,
            'sourceTour'=>$sourceTour,
            'destTour'=>$destTour,
        ]);
    }

    // In ct cho dieu hanh
    public function actionInCt($id = 0)
    {
        $theTour = Product::find()
            ->with([
                'days',
                'updatedBy',
                'bookings',
                'bookings.case',
                'bookings.case.company',
            ])
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theForm = new TourInCtForm;
        $theForm->days = '1-'.$theTour['day_count'];
        $theForm->sections = ['summary', 'itinerary', 'price'];
        $logoList = [];
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $logoList[] = [
                    'id'=>$booking['case']['company_id'],
                    'company'=>$booking['case']['company']['name'],
                    'logo'=>$booking['case']['company']['image'],
                ];
            }
        }
        $logoList[] = [
            'id'=>'amica',
            'company'=>'Amica Travel',
            'logo'=>Yii::$app->params['print_logo'],
        ]; // Agent goes first

        $logo = $logoList[0]['logo'];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (empty($theForm->sections)) {
                $theForm->sections = ['itinerary'];
            }

            if ($theForm->logo != '') {
                foreach ($theTour['bookings'] as $booking) {
                    if ($booking['case']['company_id'] == $theForm->logo) {
                        $logo = $booking['case']['company']['image'];
                    }
                }
            }

            // return $this->render('tours_in-ct_ok', [
            //     'theForm'=>$theForm,
            //     'logo'=>$logo,
            //     'theTour'=>$theTour,
            //     'theTourOld'=>$theTourOld,
            // ]);
        }

        return $this->render('tours_in-ct', [
            'theForm'=>$theForm,
            'logo'=>$logo,
            'logoList'=>$logoList,
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
        ]);
    }

    // In lich xe cho dieu hanh
    public function actionInLx($id = 0)
    {
        $theTour = Product::find()
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->with([
                'days',
                'updatedBy',
                'guides',
                'tour.cskh',
                'tour.operators',
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->with([
                'operators'=>function($q){
                    return $q->select(['id', 'name'=>new \yii\db\Expression('CONCAT(fname, " ", lname, " - ", phone)')]);
                },
            ])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theForm = new TourInLxForm;
        $theForm->days = '1-'.$theTour['day_count'];
        $theForm->giakm = 4500;
        $theForm->giadb = 1150000;
        $theForm->giatb = 1100000;
        $theForm->loaixe = '7 chỗ';
        $theForm->dieuhanh = USER_ID;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate() & !empty($_POST['noidung'])) {
            $sql = 'INSERT INTO lichxe (created_dt, created_by, updated_dt, updated_by, tour_id, name, content) VALUES (:cd, :cb, :ud, :ub, :ti, :nm, :ct)';
            Yii::$app->db->createCommand($sql, [
                ':cd'=>NOW,
                ':cb'=>USER_ID,
                ':ud'=>NOW,
                ':ub'=>USER_ID,
                ':ti'=>$theTour['id'],
                ':nm'=>$theForm['vp'],
                ':ct'=>serialize($_POST),
            ])->execute();
            return $this->render('tours_in-lx_ok', [
                'theForm'=>$theForm,
                'theTour'=>$theTour,
                'theTourOld'=>$theTourOld,
            ]);
        }

        return $this->render('tours_in-lx', [
            'theForm'=>$theForm,
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
        ]);
    }

    // In bien don khach
    public function actionInBn($id = 0)
    {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->with([
                'bookings'=>function($q) {
                    return $q->select(['id', 'case_id', 'product_id']);
                },
                'bookings.people'=>function($q) {
                    return $q->select(['id', 'fname', 'lname', 'gender']);
                },
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name', 'company_id']);
                },
                'bookings.case.company'=>function($q) {
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $names = '';
        $paxCount = 0;
        foreach ($theTour['bookings'] as $booking) {
            foreach ($booking['people'] as $pax) {
                $paxCount ++;
                $names .= $pax['lname'].' '.$pax['fname'].' '.($pax['gender'] == 'male' ? 'Mr' : 'Ms')."\n";
            }
        }

        $theForm = new PrintWelcomeBannerForm;

        $theForm->template = 'new';
        $theForm->language = 'fr';
        $theForm->pax = $paxCount.' pax';
        $theForm->names = $names;
        $theForm->logo = 'amica';
        foreach ($theTour['bookings'] as $booking) {
            if ($booking['case']['company_id'] != 0) {
                $theForm->template = 'old';
                $theForm->logo = 'other';
            }
        }

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (in_array($theForm->output, ['pdf-download', 'pdf-view'])) {
                $content = $this->renderPartial('tours_in-bn_ok', [
                    'theTour'=>$theTour,
                    'theForm'=>$theForm,
                ]);
                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    'mode' => Pdf::MODE_UTF8, 

                    'marginLeft'=>8,
                    'marginRight'=>8,
                    'marginTop'=>8,
                    'marginBottom'=>8,

                    // A4 paper format
                    'format' => Pdf::FORMAT_A4, 
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_LANDSCAPE, 
                    // stream to browser inline
                    'destination' => $theForm->output == 'pdf-download' ? Pdf::DEST_DOWNLOAD : Pdf::DEST_BROWSER,
                    'filename'=>'WELCOME-'.$theTour['op_code'].'.pdf',
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                    // 'cssFile'=>'@app/views/invoice/bootstrap.min.css',
                    // any css to be embedded if required
                    'cssInline' => '* {padding:0; border:0; margin:0;} table {width:100%;}', 
                    // set mPDF properties on the fly
                    'options' => [
                        'title' => 'WELCOME BANNER -'.$theTour['op_code'],
                        'allow_charset_conversion'=>false,
                        'autoScriptToLang'=>true,
                        'autoLangToFont'=>true,
                        'autoVietnamese'=>true,
                    ],
                    // call mPDF methods on the fly
                    'methods' => [
                        //'SetHeader'=>['INVOICE'], 
                        //'SetFooter'=>['{PAGENO}'],
                    ]
                ]);
                // return the pdf output as per the destination setting
                return $pdf->render(); 
            }
            return $this->renderPartial('tours_in-bn_ok', [
                'theTour'=>$theTour,
                'theForm'=>$theForm,
            ]);
        }

        return $this->render('tours_in-bn', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
        ]);
    }

    // Tour and tourguide points
    public function actionRatings($id = 0) {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->where(['id'=>$id, 'op_status'=>'op', 'op_finish'=>''])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // CSKH only
        if (!in_array(USER_ID, [1, 1351, 7756, 9881, 30554, 33415, 29296])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theForm = new TourRatingsForm;
        $theForm['tour_points'] = $theTourOld['pax_ratings'];

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            Yii::$app->db->createCommand()->update('at_tours', ['pax_ratings'=>$theForm['tour_points']], ['id'=>$theTourOld['id']])->execute();
            return $this->redirect('@web/tours/r/'.$theTourOld['id']);
        }

        return $this->render('tour_ratings', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
        ]);
    }

    // Feedback on anything
    // action = add | remove
    public function actionFeedback($id = 0, $action = 'add', $feedback = 0)
    {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from'])
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        if ($action == 'remove' && $feedback != 0) {
            Yii::$app->db->createCommand()->delete('at_tour_feedbacks', ['id'=>$feedback, 'created_by'=>USER_ID])->execute();
            return $this->redirect(DIR.URI);
        }

        $theFeedback = new \common\models\TourFeedback;
        if ($theFeedback->load(Yii::$app->request->post()) && $theFeedback->validate()) {
            $theFeedback->tour_id = $theTour['id'];
            $theFeedback->created_by = USER_ID;
            $theFeedback->created_dt = NOW;
            $theFeedback->save();
            return $this->redirect(DIR.URI);
        }

        $theFeedbacks = \common\models\TourFeedback::find()
            ->where(['tour_id'=>$theTour['id']])
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                }
            ])
            ->orderBy('id DESC')
            ->asArray()
            ->all();

        return $this->render('tours_feedback', [
            'theFeedback'=>$theFeedback,
            'theFeedbacks'=>$theFeedbacks,
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
        ]);
    }

    // In form feedback
    public function actionInFb($id = 0, $tcg = 'no')
    {
        $theTour = Product::find()
            ->select(['id', 'op_code', 'op_name', 'day_from', 'day_ids'])
            ->where(['id'=>$id, 'op_status'=>'op', 'op_finish'=>''])
            ->with([
                'days'=>function($q) {
                    return $q->select(['id', 'name', 'rid']);
                },
                'bookings'=>function($q) {
                    return $q->select(['id', 'product_id', 'status', 'case_id', 'product_id']);
                },
                'bookings.case'=>function($q) {
                    return $q->select(['id', 'name', 'company_id', 'is_b2b']);
                },
                'bookings.case.company'=>function($q) {
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theCompany = false;
        if (isset($theTour['bookings'][0]['case']['company'])) {
            $theCompany = $theTour['bookings'][0]['case']['company'];
            $theCompany['image'] = $theCompany['image'];
        }

        $theForm = new PrintFeedbackForm;
        $theForm->language = 'fr';
        $theForm->logoName = 'us';
        $theForm->guideNames = 'yes';
        $theForm->driverNames = 'yes';
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            $printLogo = Yii::$app->params['print_logo'];
            $printName = 'Amica Travel';

            if ($tcg == 'yes') {
                $printLogo = DIR.'assets/img/logo_tcg_263x102.jpg';
                $printName = 'Tam Coc Garden';
                $theForm['logoName'] = 'them';
            }

            if ($theCompany && in_array($theForm['logoName'], ['them', 'none'])) {
                $printLogo = $theCompany['image'];
                $printName = $theCompany['name'];
            }

            if ($theForm['logoName'] == 'voyages-villegia') {
                $theCompany['name'] = 'Voyages Villegia';
                $theCompany['image'] = Yii::getAlias('@www').'/upload/companies/2015-05/294/voyagevillegia_horiz.png';

                $printName = 'Voyages Villegia';
                $printLogo = Yii::getAlias('@www').'/upload/companies/2015-05/294/voyagevillegia_horiz.png';
            }

            return $this->renderPartial('tours_in-fb_ok_'.$theForm->language, [
                'theTour'=>$theTour,
                'theTourOld'=>$theTourOld,
                'theForm'=>$theForm,
                'printLogo'=>$printLogo,
                'printName'=>$printName,
                'theCompany'=>$theCompany,
            ]);
        }

        return $this->render('tours_in-fb', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'theCompany'=>$theCompany,
        ]);
    }

    // In bang chi phi tour
    public function actionInCf($id = 0)
    {
        $theTour = Tour::findOne($id);
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');
        }
        $theProduct = Product::find()
            ->where(['id'=>$theTour['ct_id']])
            ->with([
                'days',
                'bookings',
                'bookings.createdBy',
                'updatedBy',
            ])
            ->asArray()
            ->one();
        if (!$theProduct) {
            throw new HttpException(404, 'Tour not found');
        }

        $sql = 'SELECT tu.*, CONCAT(u.fname, " ", u.lname) AS name, u.phone FROM persons u, at_tour_user tu WHERE tu.role IN ("operator", "cservice") AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname';
        $thePeople = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryAll();

        $theCptx = Cpt::find()
            ->where(['tour_id'=>$id])
            ->with([
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->all();

        return $this->render('tours_in-cp', [
            'theTour'=>$theTour,
            'theProduct'=>$theProduct,
            'thePeople'=>$thePeople,
            'theCptx'=>$theCptx,
        ]);
    }

    // In bang chi phi cho tour guide
    public function actionInHf($id = 0)
    {
        $theTour = Product::find()
            ->with([
                'days',
                'updatedBy',
                'bookings',
                'bookings.createdBy',
            ])
            ->where(['id'=>$id, 'op_status'=>'op'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Tour guide list
        $sql = 'select guide_user_id, guide_name from at_tour_guides where tour_id=:tour_id AND parent_id=0 limit 100';
        $tourguideList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Tour driver list
        $sql = 'select driver_user_id, driver_name from at_tour_drivers where tour_id=:tour_id AND parent_id=0 limit 100';
        $driverList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Payer list
        $sql = 'select payer from cpt where tour_id=:tour_id group by payer order by payer limit 100';
        $payerList = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

        $theForm = new TourInHdForm;
        $theForm->days = '1-'.$theTour['day_count'];
        $theForm->payer = 'Hướng dẫn MB 1';
        $theForm->language = Yii::$app->language;

        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            if (empty($theForm->options)) {
                $theForm->options = [];
            }
            $sql = 'SELECT *,
                IF (via_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=via_company_id LIMIT 1)) AS via_company_name,
                IF (by_company_id=0, "", (SELECT name FROM at_companies c WHERE c.id=by_company_id LIMIT 1)) AS by_company_name,
                IF (venue_id=0, "", (SELECT name FROM venues v WHERE v.id=venue_id LIMIT 1)) AS venue_name,
                1
                FROM cpt WHERE (latest=dvtour_id OR latest=0) AND tour_id=:tour_id ORDER BY dvtour_day, dvtour_name, updated_at';
            $theCptx = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

            // Get exchange rates
            $xRates = [
                'USD'=>22295,
                'VND'=>1,
            ];
            $sql = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1';
            $theXRate = Yii::$app->db->createCommand($sql)->queryScalar();
            if ($theXRate) {
                $xRates['USD'] = $theXRate;
            }

            return $this->render('tours_in-hd_ok', [
                'theTour'=>$theTour,
                'theTourOld'=>$theTourOld,
                'theForm'=>$theForm,
                'tourguideList'=>$tourguideList,
                'driverList'=>$driverList,
                'payerList'=>$payerList,
                'theCptx'=>$theCptx,
                'xRates'=>$xRates,
            ]);
        }

        return $this->render('tours_in-hd', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'tourguideList'=>$tourguideList,
            'driverList'=>$driverList,
            'payerList'=>$payerList,
        ]);
    }

    public function actionNhadan()
    {
        $venueList = [
            1563=>'Boping, Kompong Thom, ghép',
            1197=>'Cầu, Tùng Bá, không ghép',
            1672=>'Chiến, Bảo Lạc, không ghép',
            2193=>'Chớ, Mù Căng Chải, ghép max 2 đoàn/10p',
            942=>'Cư, Nộn Khê, không ghép',
            2168=>'Đảo Cò, Hải Dương, ghép',
            459=>'Hải, Bảo Lạc, không ghép',
            1779=>'Hùng, Ba Bể, không ghép',
            616=>'Ích, Nộn Khê, không ghép',
            2063=>'Kính, Hà Giang, ghép max 12p',
            1191=>'Liễu, Lũng Lai, không ghép',
            1604=>'Loma, Phong Sali, Laos, 12',
            1605=>'Lungton, Phong Sali, Laos, 6+4+4',
            1577=>'Ngoan, Nậm Ngùa, không ghép',
            807=>'Nguyên, Huế, ghép',
            1603=>'Opa, Phong Sali, Laos, 7+4+4',
            455=>'Pà Chi, Bắc Hà, ghép',
            2074=>'Pheng, Phong Sali, Laos, max 10, không ghép',
            1192=>'Phin, Vai Thai / Sạc Xậy, không ghép',
            1852=>'Phong, Bắc Hà, ghép, max 13',
            1583=>'Phương, Bảo Lạc, không ghép',
            259=>'Phượng, Nghĩa Lộ, không ghép',
            1369=>'Quỳnh, Hà Giang, ghép',
            310=>'Sa, Bắc Hà, ghép',
            1126=>'San, Siem Reap, ghép',
            751=>'Sáng, Bắc Hà, ghép',
            1198=>'Sỹ, Séo Lủng, không ghép',
            1023=>'Tam Coc Garden, Ninh Binh, ghép',
            752=>'Tập, Ba Bể, ghép',
            581=>'Thành, Hồng Phong, không ghép',
            2082=>'Thiện, Hà Nội, không ghép, không ngủ',
            1054=>'Thuyết Nhung, Mai Châu, ? ghép',
            452=>'Tư, Mù Căng Chải, ghép',
            1193=>'Tưng, Nậm Ngùa, không ghép',
            1400=>'Việt, Bến Tre, ghép',
        ];
        $yearList = [2018=>2018, 2017=>2017, 2016=>2016, 2015=>2015, 2014=>2014, 2013=>2013];

        // Add or remove avils
        if (isset($_POST['action'], $_POST['venue_id'], $_POST['day'], $_POST['note']) && $_POST['action'] == 'add-avail' && trim($_POST['note']) != '') {
            if (!array_key_exists($_POST['venue_id'], $venueList)) {
                throw new HttpException(404, 'Venue not found');
            }
            Yii::$app->db->createCommand()
                ->insert('at_avails', [
                    'created_at'=>NOW,
                    'created_by'=>USER_ID,
                    'stype'=>'wait',
                    'rtype'=>'venue',
                    'rid'=>$_POST['venue_id'],
                    'from_dt'=>$_POST['day'],
                    'note'=>(isset($_POST['pax']) && trim($_POST['pax']) != '' ? '('.trim($_POST['pax']).') ' : '').trim($_POST['note']),
                ])
                ->execute();
            return $this->redirect('@web/tours/nhadan?venue='.$_POST['venue_id'].'&year='.substr($_POST['day'], 0, 4));
        }

        if (isset($_GET['action'], $_GET['id'], $_GET['venue_id'], $_GET['year']) && $_GET['action'] == 'remove-avail') {
            Yii::$app->db->createCommand()
                ->delete('at_avails', [
                    'id'=>$_GET['id'],
                    'created_by'=>Yii::$app->user->id,
                ])
                ->execute();
            return $this->redirect('@web/tours/nhadan?venue='.$_GET['venue_id'].'&year='.$_GET['year']);
        }

        $getVenue = Yii::$app->request->get('venue', 616);
        $getYear = Yii::$app->request->get('year', date('Y'));
        $getView = Yii::$app->request->get('view', 'year');
        if ($getView != 'year') {
            $getView = 'month';
        }

        if (!array_key_exists($getVenue, $venueList)) {
            throw new HttpException(404, 'Venue not found');
        }
        if (!array_key_exists($getYear, $yearList)) {
            throw new HttpException(404, 'Invalid year');
        }

        $theVenue = Venue::find()
            ->where(['id'=>$getVenue])
            ->asArray()
            ->one();
        $theCptx = Cpt::find()
            ->where(['venue_id'=>$theVenue['id']])
            ->andWhere('YEAR(dvtour_day)=:year', [':year'=>$getYear])
            ->with([
                'tour'=>function($q)
                {
                    return $q->select(['id', 'code', 'name', 'ct_id']);
                },
                'updatedBy'=>function($q)
                {
                    return $q->select(['id', 'name']);
                },
            ])
            ->orderBy($getView == 'year' ? 'tour_id, dvtour_day' : 'dvtour_day')
            ->asArray()
            ->limit(2500)
            ->all();
        $theWaits = Yii::$app->db->createCommand('SELECT a.*, u.name AS username FROM at_avails a, persons u WHERE u.id=a.created_by AND  a.stype="wait" AND a.rtype="venue" AND a.rid=:id AND YEAR(a.from_dt)=:year', [':id'=>$getVenue, ':year'=>$getYear])
            ->queryAll();

        $ctIdList = [];
        foreach ($theCptx as $cpt) {
            $ctIdList[] = $cpt['tour']['ct_id'];
        }
        $guideList = [];
        if (!empty($ctIdList)) {
            $sql = 'select tour_id, guide_name, use_from_dt, use_until_dt from at_tour_guides WHERE tour_id IN ('.implode(',', $ctIdList).')';
            $guideList = Yii::$app->db->createCommand($sql)->queryAll();
        }
        return $this->render('tours_nhadan', [
            'theVenue'=>$theVenue,
            'theCptx'=>$theCptx,
            'theWaits'=>$theWaits,
            'getVenue'=>$getVenue,
            'getYear'=>$getYear,
            'getView'=>$getView,
            'venueList'=>$venueList,
            'yearList'=>$yearList,
            'guideList'=>$guideList,
        ]);
    }

    public function actionServices($id = 0, $filter = '', $code = '')
    {
        // Go to another code
        if ($code != '') {
            $theTour = Tour::find()
                ->select(['ct_id'])
                ->andWhere(['like', 'code', $code])
                ->orderBy('day_from DESC')
                ->asArray()
                ->one();
            if ($theTour) {
                return $this->redirect('/tours/services/'.$theTour['ct_id']);
            }
        }

        $theTourOld = Tour::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found');         
        }

        // Find tour
        $theTour = Product::find()
            ->where(['id'=>$theTourOld['ct_id']])
            ->andWhere(['op_status'=>'op'])
            ->with([
                'days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour program not found');         
        }

        $theCptx = Cpt::find()
            ->where(['tour_id'=>$theTourOld['id']])
            ->andWhere(['or', 'latest=0', 'latest=dvtour_id'])
            ->with([
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'cp'=>function($q) {
                    return $q->select(['id', 'name', 'venue_id', 'by_company_id']);
                },
                'cp.venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'cp.company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        $sStatus = [
            'n'=>'Chưa đặt',
            'x'=>'Bị huỷ',
            'k'=>'OK'
        ];

        // Get exchange rates
        $sql3 = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1';
        $xRates['USD'] = Yii::$app->db->createCommand($sql3)->queryOne();

        $allVenues = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();;

        $allCompanies = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // Tour operators
        $sql5 = 'SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname LIMIT 100';
        $tourOperators = Yii::$app->db->createCommand($sql5, [':id'=>$theTourOld['id']])->queryAll();

        $tourOperatorIds = [];
        foreach ($tourOperators as $to) {
            $tourOperatorIds[] = $to['user_id'];
        }

        // Guides in this tour
        $sql6 = 'SELECT u.id, u.fname, u.lname, u.about AS uabout, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:id ORDER BY day LIMIT 100';
        $tourGuides = Yii::$app->db->createCommand($sql6, [':id'=>$theTour['id']])->queryAll();

        return $this->render('tour_costs', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theCptx'=>$theCptx,
            'sStatus'=>$sStatus,
            'xRates'=>$xRates,
            'allCompanies'=>$allCompanies,
            'allVenues'=>$allVenues,
            'tourOperators'=>$tourOperators,
            'tourOperatorIds'=>$tourOperatorIds,
            'tourGuides'=>$tourGuides,
            'filter'=>$filter,
        ]);
    }

    // public function actionCosts($id = 0, $filter = '', $code = '')
    public function actionCosts()
    {
        $id = 1234;
        if (!is_int($id)) {
            $theTourOld = Tour::find()
                ->where(['code'=>strtoupper($id)])
                ->asArray()
                ->one();
            if (!$theTourOld) {
                throw new HttpException(404, 'Tour not found');
            }
            return $this->redirect('/tours/costs/'.$theTourOld['id']);
        }

        // Go to another code
        /*
        if ($code != '') {
            $theTour = Product::find()
                ->select(['id'])
                ->where(['op_status'=>'op'])
                ->andWhere(['like', 'op_code', $code])
                ->orderBy('day_from DESC')
                ->asArray()
                ->one();
            if ($theTour) {
                return $this->redirect('@web/tours/costs/'.$theTour['id']);
            }
        }

        // Find tour
        $theTour = Product::find()
            ->where(['id'=>$id])
            ->andWhere(['op_status'=>'op'])
            ->with([
                'days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');         
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$theTour['id']])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found');         
        }

        */

        // Go to another code
        if ($code != '') {
            $theTour = Tour::find()
                ->select(['id'])
                ->andWhere(['like', 'code', $code])
                ->orderBy('id DESC')
                ->asArray()
                ->one();
            if ($theTour) {
                return $this->redirect('/tours/costs/'.$theTour['id']);
            }
        }

        $theTourOld = Tour::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found');         
        }

        // Find tour
        $theTour = Product::find()
            ->where(['id'=>$theTourOld['ct_id']])
            ->andWhere(['op_status'=>'op'])
            ->with([
                'days',
                ])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour program not found');         
        }

        $theCptx = Cpt::find()
            ->where(['tour_id'=>$theTourOld['id']])
            ->andWhere(['or', 'latest=0', 'latest=dvtour_id'])
            ->with([
                'company'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                // 'cp'=>function($q) {
                //     return $q->select(['id', 'name', 'unit', 'venue_id', 'by_company_id']);
                // },
                // 'cp.venue'=>function($q) {
                //     return $q->select(['id', 'name']);
                // },
                // 'cp.company'=>function($q) {
                //     return $q->select(['id', 'name']);
                // },
                'viaCompany'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'venue'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'comments',
                'comments.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->orderBy('dvtour_day')
            ->asArray()
            ->all();

        $sStatus = [
            'n'=>'Chưa đặt',
            'x'=>'Bị huỷ',
            'k'=>'OK'
        ];

        // Get exchange rates
        $sql3 = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$theTour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1';
        $xRates['USD'] = Yii::$app->db->createCommand($sql3)->queryOne();

        $allVenues = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();;

        $allCompanies = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // Tour operators
        $sql5 = 'SELECT tu.*, u.name FROM persons u, at_tour_user tu WHERE tu.role="operator" AND tu.user_id=u.id AND tu.tour_id=:id ORDER BY u.lname LIMIT 100';
        $tourOperators = Yii::$app->db->createCommand($sql5, [':id'=>$theTourOld['id']])->queryAll();

        $tourOperatorIds = [];
        foreach ($tourOperators as $to) {
            $tourOperatorIds[] = $to['user_id'];
        }

        // Guides in this tour
        $sql6 = 'SELECT u.id, u.fname, u.lname, u.about AS uabout, tg.* FROM persons u, at_tour_guide tg WHERE tg.user_id=u.id AND tg.tour_id=:id ORDER BY day LIMIT 100';
        $tourGuides = Yii::$app->db->createCommand($sql6, [':id'=>$theTour['id']])->queryAll();

        return $this->render('tour_costs', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theCptx'=>$theCptx,
            'sStatus'=>$sStatus,
            'xRates'=>$xRates,
            'allCompanies'=>$allCompanies,
            'allVenues'=>$allVenues,
            'tourOperators'=>$tourOperators,
            'tourOperatorIds'=>$tourOperatorIds,
            'tourGuides'=>$tourGuides,
            'filter'=>$filter,
        ]);
    }

    public function actionTongcp($month = 0, $payer = '', $orderby = 'tourcode')
    {
        if (strlen($month) != 7) {
            $month = date('Y-m');
        }

        // Danh sách tour
        $sql = 'SELECT ct.pax, ct.day_count, ct.day_from, t.code, t.name, t.status, t.ct_id, t.id, t.se
          FROM at_ct ct, at_tours t WHERE ct.id=t.ct_id AND SUBSTRING(day_from, 1, 7)=:ym ORDER BY '.($orderby == 'tourcode' ? 'SUBSTRING(code,2,7)' : 'day_from, SUBSTRING(code,2,7)').' LIMIT 1000';
        $theTours = Yii::$app->db->createCommand($sql, [':ym'=>$month])->queryAll();

        $tourIdList = [];
        $result = [];
        $usdRates = [];
        foreach ($theTours as $tour) {
            $tourIdList[] = $tour['id'];
            $result[$tour['id']] = 0;

            // USD-VND rates
            $sql = 'SELECT rate FROM at_xrates WHERE currency2="VND" AND currency1="USD" AND rate_dt<="'.$tour['day_from'].'" ORDER BY rate_dt DESC LIMIT 1';
            //$usdRates[$tour['id']] = Yii::$app->db->createCommand($sql)->queryScalar();
            //if ($usdRates[$tour['id']] == 0) {
                $usdRates[$tour['id']] = 21000;
            //}
        }

        $xRates['EUR'] = 24300;
        $xRates['USD'] = 21300;
        $xRates['VND'] = 1;

        // Cac chi phi cua tour
        if ($payer == 'bunthol') {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN ('.implode(',', $tourIdList).') AND (latest=0 OR latest=dvtour_id) AND payer="Bunthol" ORDER BY tour_id';
        } elseif ($payer == 'itravellaos') {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN ('.implode(',', $tourIdList).') AND (latest=0 OR latest=dvtour_id) AND payer="iTravelLaos" ORDER BY tour_id';
        } else {
            $sql = 'SELECT * FROM cpt WHERE tour_id IN ('.implode(',', $tourIdList).') AND (latest=0 OR latest=dvtour_id) ORDER BY tour_id';
        }
        $theCptx = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($theCptx as $cp) {
            if ($cp['latest']==0) {
                if (in_array($payer, ['bunthol', 'itravellaos'])) {
                    $sub = $cp['qty']*$cp['price']*(1+$cp['vat']/100);
                } else {
                    if ($cp['unitc'] == 'USD') {
                        $sub = $cp['qty']*$cp['price']*$usdRates[$cp['tour_id']]*(1+$cp['vat']/100);
                    } else {
                        $sub = $cp['qty']*$cp['price']*$xRates[$cp['unitc']]*(1+$cp['vat']/100);
                    }
                }

                if ($cp['plusminus'] == 'minus') {
                    $sub = -$sub;
                }
                $result[$cp['tour_id']] += $sub;
            }   
        }

        return $this->render('tours_tongchiphi', [
            'month'=>$month,
            'result'=>$result,
            'theTours'=>$theTours,
            'theCptx'=>$theCptx,
        ]);
    }

    public function actionCmd($id)
    {
        $theTour = Product::find()
            ->where(['op_status'=>'op', 'id'=>$id])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found '.$id);
        }
        return $this->render('tours_cmd', [
            'theTour'=>$theTour,
        ]);
    }

    // Test tour by day
    public function actionTest($id = 0)
    {
        $theTour = Product::find()
            ->where(['id'=>$id])
            ->with([
                'days',
                'tournotes',
                'tournotes.updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->one();

        /*$sql = 'select n.id, n.title, n.from_id, n.body from at_messages n, at_relations r where r.otype="note" and r.oid=n.id and r.rtype="case" and r.rid=:rid order by co desc';
        $theNotes = \common\models\Note::findBySql($sql, [
                ':rid'=>22319,
            ])
            ->with([
                'from'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'to'=>function($q) {
                    return $q->select(['id', 'name']);
                },
            ])
            ->asArray()
            ->all();

        \fCore::expose($theNotes);
        exit;*/

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        if (!in_array(USER_ID, [1])) {
            // throw new HttpException(403, 'Access denied.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Drivers and vehicles
        $sql = 'select *, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourDrivers = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Guides
        $sql = 'select *, IF(guide_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourguides = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Notes
        $sql = 'select *, IF(driver_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=driver_user_id LIMIT 1)) AS namephone from at_tour_drivers where tour_id=:tour_id order by use_from_dt limit 100';
        $theTourNotes = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        $theForm = new \app\models\TourTestForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            echo 'DONE';
            exit;
        }

        return $this->render('tours_test', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theTourDrivers'=>$theTourDrivers,
            'theTourguides'=>$theTourguides,
            'theForm'=>$theForm,
        ]);
    }

    // Assign tour guides
    public function actionGuides($id = 0, $action = 'add', $item_id = 0)
    {
        $theTour = Product::find()
            ->where(['id'=>$id])
            ->with(['days'])
            ->asArray()
            ->one();

        if (!$theTour) {
            throw new HttpException(404, 'Tour itinerary not found.');
        }

        $theTourOld = Tour::find()
            ->where(['ct_id'=>$id, 'status'=>'on'])
            ->asArray()
            ->one();

        if (!$theTourOld) {
            throw new HttpException(404, 'Tour not found.');
        }

        // Guides for this tour
        $sql = 'select *, guide_name, guide_user_id, IF(guide_user_id=0, "", (SELECT CONCAT(name, " - ", REPLACE(phone, " ", "")) FROM persons u WHERE u.id=guide_user_id LIMIT 1)) AS namephone from at_tour_guides where tour_id=:tour_id order by use_from_dt limit 100';
        $tourGuides = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTour['id']])->queryAll();

        // Tour guide list
        $sql = 'select u.id, CONCAT(u.name, " - ", REPLACE(u.phone, " ", "")) AS namephone from persons u, at_profiles_tourguide p where u.id=p.user_id order by u.lname, u.fname limit 3000';
        $theGuides = Yii::$app->db->createCommand($sql)->queryAll();

        $theGuide = false;

        // Check action
        if (
            !in_array($action, ['add', 'addtime', 'edit', 'delete'])
            || (in_array($action, ['addtime', 'edit', 'delete']) && $item_id == 0)
        ) {
            return $this->redirect(DIR.URI);
        }


        // action add
        if ($action == 'add') {
            $theForm = new TourGuideForm;
            $theForm->bookingStatus = 'confirmed';
            $theForm->useTimezone = 'Asia/Ho_Chi_Minh';
            $theForm->useFromDt = $theTour['day_from'].' 08:00'; 
            $theForm->useUntilDt = date('Y-m-d', strtotime('+ '.($theTour['day_count'] - 1).' days', strtotime($theTour['day_from']))).' 22:00';

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $guideUserId = 0;
                foreach ($theGuides as $guide) {
                    if ($theForm['guideName'] == trim($guide['namephone'])) {
                        $guideUserId = $guide['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->insert('at_tour_guides', [
                    'created_dt'=>NOW,
                    'created_by'=>USER_ID,
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'tour_id'=>$theTour['id'],
                    'guide_company'=>$theForm['guideCompany'],
                    'guide_name'=>$theForm['guideName'],
                    'guide_user_id'=>$guideUserId,
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ])->execute();

                return $this->redirect(DIR.URI);
            }
        }

        // action add time
        if ($action == 'addtime' && $item_id != 0) {
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            if (!in_array(USER_ID, [1, 118, 29296, 33415, $theGuide['created_by'], $theGuide['updated_by']])) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourGuideForm;

            $theForm->useTimezone = $theGuide['use_timezone'];
            $theForm->guideCompany = $theGuide['guide_company'];
            $theForm->guideName = $theGuide['guide_name'];
            $theForm->useFromDt = $theGuide['use_from_dt'];
            $theForm->useUntilDt = $theGuide['use_until_dt'];
            $theForm->bookingStatus = $theGuide['booking_status'];
            $theForm->points = $theGuide['points'];
            $theForm->note = $theGuide['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                Yii::$app->db->createCommand()->insert('at_tour_guides', [
                    'created_dt'=>NOW,
                    'created_by'=>USER_ID,
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'parent_id'=>$item_id,
                    'tour_id'=>$theTour['id'],
                    'guide_company'=>$theForm['guideCompany'],
                    'guide_name'=>$theForm['guideName'],
                    'guide_user_id'=>$theGuide['guide_user_id'],
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    //'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ])->execute();

                return $this->redirect(DIR.URI);
            }
        }

        // action edit
        if ($action == 'edit' && $item_id != 0) {
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            $allowEditList = [1, 118, 29296, 33415, $theGuide['created_by'], $theGuide['updated_by']];

            // Tour ops
            $sql = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);


            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            $theForm = new TourGuideForm;

            $theForm->useTimezone = $theGuide['use_timezone'];
            $theForm->guideCompany = $theGuide['guide_company'];
            $theForm->guideName = $theGuide['guide_name'];
            $theForm->useFromDt = $theGuide['use_from_dt'];
            $theForm->useUntilDt = $theGuide['use_until_dt'];
            $theForm->bookingStatus = $theGuide['booking_status'];
            $theForm->points = $theGuide['points'];
            $theForm->note = $theGuide['note'];

            if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
                // Check if driver exists
                $guideUserId = 0;
                foreach ($theGuides as $guide) {
                    if ($theForm['guideName'] == trim($guide['namephone'])) {
                        $guideUserId = $guide['id'];
                        break;
                    }
                }

                Yii::$app->db->createCommand()->update('at_tour_guides', [
                    'updated_dt'=>NOW,
                    'updated_by'=>USER_ID,
                    'guide_company'=>$theForm['guideCompany'],
                    'guide_name'=>$theGuide['guide_user_id'] != 0 ? $theGuide['guide_name'] : $theForm['guideName'],
                    'guide_user_id'=>$theGuide['guide_user_id'] != 0 ? $theGuide['guide_user_id'] : $guideUserId,
                    'use_from_dt'=>$theForm['useFromDt'],
                    'use_until_dt'=>$theForm['useUntilDt'],
                    'use_timezone'=>$theForm['useTimezone'],
                    'booking_status'=>$theForm['bookingStatus'],
                    'points'=>$theForm['points'],
                    'note'=>$theForm['note'],
                ], ['id'=>$item_id])->execute();
                Yii::$app->session->setFlash('success', 'Guide info has been updated: '.$theGuide['guide_name']);
                return $this->redirect(DIR.URI);
            }
        }

        // action delete
        if ($action == 'delete' && $item_id != 0) {
            $theForm = false;
            foreach ($tourGuides as $guide) {
                if ($guide['id'] == $item_id) {
                    $theGuide = $guide;
                }
            }

            if (!$theGuide) {
                throw new HttpException(404, 'Guide info not found');
            }

            $allowEditList = [1, 118, 29296, $theGuide['created_by'], $theGuide['updated_by']];

            // Tour ops
            $sql = 'SELECT user_id FROM at_tour_user WHERE tour_id=:tour_id AND role="operator"';
            $tourOpIds = Yii::$app->db->createCommand($sql, [':tour_id'=>$theTourOld['id']])->queryAll();

            foreach ($tourOpIds as $opId) {
                $allowEditList[] = $opId['user_id'];
            }

            $allowEditList = array_unique($allowEditList);

            if (!in_array(USER_ID, $allowEditList)) {
                throw new HttpException(403, 'Access denied');
            }

            //if (Yii::$app->request->post('confirm') == 'delete') {
                Yii::$app->db->createCommand()->delete('at_tour_guides', ['parent_id'=>$item_id])->execute();
                Yii::$app->db->createCommand()->delete('at_tour_guides', ['id'=>$item_id])->execute();
                Yii::$app->session->setFlash('success', 'Guide info has been deleted: '.$theGuide['guide_name']);
                return $this->redirect(DIR.URI);
            //}
        }
    
        return $this->render('tours_guides', [
            'theTour'=>$theTour,
            'theTourOld'=>$theTourOld,
            'theForm'=>$theForm,
            'tourGuides'=>$tourGuides,
            'theGuide'=>$theGuide,
            'theGuides'=>$theGuides,
            'action'=>$action,
            'item_id'=>$item_id,
        ]);
    }

    public function actionGuides2($id = 0)
    {
        if ($id != 333) {
            die('REQUIRES 333');
        }
        $sql = 'select * from at_tour_guide order by tour_id, user_id, day';
        $tgx = Yii::$app->db->createCommand($sql)->queryAll();
        $ntg = [
            'tour_id'=>0,
            'guide_id'=>0,
            'points'=>0,
            'from'=>0,
            'until'=>0,
        ];
        Yii::$app->db->createCommand('truncate table at_tour_guides')->execute();
        foreach ($tgx as $tg) {
            if ($tg['ct_id'] == $ntg['tour_id'] && $tg['user_id'] == $ntg['guide_id']) {
                if ((int)$tg['pax_ratings'] != 0) {
                    $ntg['points'] = (int)$tg['pax_ratings'];
                }
                if (date('Y-m-d', strtotime('+1 days', strtotime($ntg['until']))) != $tg['day']) {
                    echo '<br>tour id=', $tg['tour_id'];
                }
                $ntg['until'] = $tg['day'].' 22:00';
            } else {
                if ($ntg['tour_id'] != 0) {
                    Yii::$app->db->createCommand()->insert('at_tour_guides', [
                        'created_dt'=>$ntg['uo'],
                        'created_by'=>$ntg['ub'],
                        'updated_dt'=>$ntg['uo'],
                        'updated_by'=>$ntg['ub'],
                        'points'=>$ntg['points'],
                        'tour_id'=>$ntg['tour_id'],
                        'guide_user_id'=>$ntg['guide_id'],
                        'use_from_dt'=>$ntg['from'],
                        'use_until_dt'=>$ntg['until'],
                        'use_timezone'=>'Asia/Ho_Chi_Minh',
                        'booking_status'=>'confirmed',
                    ])->execute();
                    echo '<br>ITEM '.$tg['id'].' SAVED.';
                }
                $ntg = [
                    'uo'=>$tg['uo'],
                    'ub'=>$tg['ub'],
                    'tour_id'=>$tg['ct_id'],
                    'guide_id'=>$tg['user_id'],
                    'points'=>(int)$tg['pax_ratings'],
                    'from'=>$tg['day'].' 08:00',
                    'until'=>$tg['day'].' 22:00',
                ];
            }
        }
        echo '<br>DONE!';
    }



    // Create tour notes for calendar
    public function actionCtn($id = 0)
    {
        $theTour = Product::find()
            ->where(['op_status'=>'op', 'id'=>$id])
            ->with(['days'])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found');         
        }
        $theNote = Tournote::find()
            ->where(['product_id'=>$id, 'created_by'=>USER_ID])
            ->one();
        if (!$theNote) {
            $theNote = new Tournote;
            $theNote->product_id = $theTour['id'];
            $theNote->created_at = NOW;
            $theNote->created_by = USER_ID;
            $theNote->updated_at = NOW;
            $theNote->updated_by = USER_ID;
        }

        if ($theNote->load(Yii::$app->request->post()) && $theNote->validate()) {
            $theNote->save();
            return $this->redirect('/products/op/'.$theTour['id']);
        }

        return $this->render('tours_ctn', [
            'theTour'=>$theTour,
            'theNote'=>$theNote,
        ]);
    }
}
