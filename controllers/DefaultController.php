<?
namespace app\controllers;

use common\models\Note;
use common\models\Search;
use common\models\Person;
use common\models\User;
use common\models\User2;
use common\models\Message;

use Yii;
use yii\data\Pagination;
use yii\web\Response;


class DefaultController extends MyController
{
    public function actionIndex()
    {
        // List of sellers
        $sellerList = [];
        if (\app\helpers\User::inGroups('any:it,lanhdao,banhang')) {
            $sql = 'SELECT u.id, u.fname, u.lname, u.email FROM users u, at_cases c WHERE u.status="on" AND c.owner_id=u.id GROUP BY c.owner_id ORDER BY u.lname, u.fname';
            $sellerList = Yii::$app->db->createCommand($sql)->queryAll();
        }
        // New tour, Huan & NgocHB
        $theTours = [];
        if (in_array(Yii::$app->user->id, [1, 118, 8162, 29212])) {
            $theTours = Yii::$app->db
                ->createCommand('select t.id, t.code, t.se, t.uo, ct.pax, ct.day_from, ct.day_count, (select name from persons where id=se limit 1) as se_name from at_tours t, at_ct ct where ct.id=t.ct_id AND t.status="draft" AND op!=1 order by ct.day_from')
                ->queryAll();
        }

        // 170901 Them Anh Tho, Ngoc Linh
        if (in_array(Yii::$app->user->id, [1,2,3,4,118,695,4432,17090,6,29212])) {
            // All notes
            $theMessages = Message::find()
                ->select('via, id, co, cb, uo, ub, title, from_id, rtype, rid, priority')
                ->with([
                    'from'=>function($q) {
                        return $q->select(['id', 'nickname', 'image']);
                    },
                    'to'=>function($q) {
                        return $q->select(['id', 'nickname', 'image']);
                    },
                    'relatedCase'=>function($q) {
                        return $q->select(['id', 'name']);
                    },
                    'relatedTour'=>function($q) {
                        return $q->select(['id', 'name', 'code']);
                    },
                    ])
                ->orderBy('uo DESC')
                ->limit(20)
                ->asArray()
                ->all();
        } else {
            // Kiem tra nhung message cua toi gui hoac gui cho toi
            // 1 - Doc id 20 message gui cho toi
            // 2 - Doc id 20 message toi gui va khac 20 message noi tren
            // 3 - Doc cac message voi id ghep nhu tren, lay 20 muc gan nhat
            // Khong hop ly lam vi khong dung thu tu cua messages
            // $sql1 = 'SELECT message_id FROM at_message_to WHERE user_id = :me ORDER BY message_id DESC LIMIT 20';
            // $msgIdList1 = Yii::$app->db->createCommand($sql1, [':me'=>USER_ID])->queryColumn();
            // $sql2 = 'SELECT from_id FROM at_messages WHERE from_id = :me ORDER BY id DESC LIMIT 20';
            // $msgIdList2 = Yii::$app->db->createCommand($sql1, [':me'=>USER_ID])->queryColumn();
            // $msgIdList = array_merge($msgIdList1, $msgIdList2);
            $theMessages = Message::find()
                ->select('at_messages.via, at_messages.id, at_messages.co, at_messages.cb, at_messages.uo, at_messages.ub, at_messages.title, at_messages.from_id, at_messages.rtype, at_messages.rid, at_messages.priority')
                // ->where(['id'=>$msgIdList])
                ->innerJoinWith([
                    'sto'=>function($q) {
                        return $q->andWhere(['users.id'=>USER_ID]);
                    },
                ])
                ->with([
                    'from'=>function($q) {
                        return $q->select(['id', 'nickname', 'image']);
                    },
                    'to'=>function($q) {
                        return $q->select(['id', 'nickname', 'image']);
                    },
                    'relatedCase'=>function($q) {
                        return $q->select(['id', 'name']);
                    },
                    'relatedTour'=>function($q) {
                        return $q->select(['id', 'name', 'code']);
                    },
                    ])
                ->orderBy('uo DESC')
                ->limit(20)
                ->asArray()
                ->all();
        }

        // The tasks
        $theTasks = Yii::$app->db
            ->createCommand('SELECT t.*, u.name AS ub_name FROM at_tasks t, users u, at_task_user tu WHERE t.status="on" AND tu.completed_dt=0 AND u.id=t.ub AND tu.task_id=t.id AND tu.user_id=:id ORDER BY fuzzy, SUBSTRING(t.due_dt,1,16), t.is_priority LIMIT 15', [':id'=>Yii::$app->user->id])
            ->queryAll();

        // Task id list
        foreach ($theTasks as $t) {
            $theTaskIdList[] = $t['id'];
        }

        // The task users
        if (empty($theTaskIdList)) {
            $theTaskUsers = [];
        } else {
            $theTaskUsers = Yii::$app->db
                ->createCommand('SELECT u.name AS user_name, tu.* FROM users u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname')
                ->queryAll();
        }

        // Online users
        $onlineUsers = Yii::$app->db
            ->createCommand('SELECT u.id, u.nickname, u.image FROM users u, at_online_users o WHERE o.user_id=u.id AND o.dt > DATE_SUB(:now, INTERVAL 15 MINUTE) GROUP BY u.id ORDER BY dt DESC LIMIT 100', [':now'=>NOW])
            ->queryAll();

        // Starred items
        $theStarredItems = [];/*Yii::$app->db
            ->createCommand('SELECT rtype, rid, name FROM at_stars WHERE stype="s" AND ub=:id ORDER BY uo DESC LIMIT 10', [':id'=>Yii::$app->user->id])
            ->queryAll();*/

        $theViewedItems = []; /*Yii::$app->db
            ->createCommand('SELECT rtype, rid, name FROM at_stars WHERE stype="v" AND ub=:id ORDER BY uo DESC LIMIT 10', [':id'=>Yii::$app->user->id])
            ->queryAll();*/

        // onLeaves
        $absentPeople = Yii::$app->db
            ->createCommand('SELECT u.id, u.image, u.name, e.name AS e_name, e.from_dt, e.until_dt FROM persons u, events e, at_event_user eu WHERE e.status="on" AND u.id=eu.user_id AND eu.event_id=e.id AND e.from_dt>=:today AND e.from_dt<=:tomorrow ORDER BY from_dt LIMIT 20', [':today'=>date('Y-m-d 00:00:00', strtotime('- 1 days')), ':tomorrow'=>date('Y-m-d 23:59:59', strtotime('+ 2 days'))])
            ->queryAll();

        // Newly-asigned cases
        $newlyAssignedCases = Yii::$app->db
            ->createCommand('SELECT c.id, c.name, c.ao FROM at_cases c WHERE c.owner_id!=0 ORDER BY c.id DESC LIMIT 5')
            ->queryAll();
        // Newly-asigned tours
        // New payments
        $newPayments = Yii::$app->db
            ->createCommand('SELECT p.*, u.name AS updated, t.code AS tour_code, t.id AS tour_id FROM at_payments p, at_bookings b, at_tours t, persons u WHERE u.id=p.updated_by AND b.id=p.booking_id AND t.ct_id=b.product_id AND b.created_by=:id ORDER BY p.updated_at DESC LIMIT 5', [':id'=>Yii::$app->user->id])
            ->queryAll();
            // $this->layout = 'limitless';

        return $this->render('home', [
            'theMessages'=>$theMessages,
            'theTours'=>$theTours,
            'onlineUsers'=>$onlineUsers,
            'theTasks'=>$theTasks,
            'theTaskUsers'=>$theTaskUsers,
            'theStarredItems'=>$theStarredItems,
            'theViewedItems'=>$theViewedItems,
            'absentPeople'=>$absentPeople,
            'newlyAssignedCases'=>$newlyAssignedCases,
            'newPayments'=>$newPayments,
            'sellerList'=>$sellerList,
        ]);
    }

    public function actionCkfinder($ckf = '', $ui = 'compact', $width = '100%', $height = '')
    {
        // \fCore::expose($_SESSION);
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"><title>File Browser</title></head><body><script src="/assets/ckfinder_3.4.1/ckfinder.js?ckf='.$ckf.'&v=2"></script><script>CKFinder.start({'.($ui == 'compact' ? 'displayFoldersPanel:false' : '').'});</script></body></html>';
    }

    public function actionError()
    {
        $this->layout = 'pure';
        return $this->render('error', [
            'name'=>$name,
        ]);
    }

    // 
    public function actionSelectLang($lang = 'en')
    {
        if (in_array($lang, Yii::$app->params['active_languages'])) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->set('active_language', $lang);
            } else {
                Yii::$app->db->createCommand()->update('users', ['language'=>$lang], ['id'=>MY_ID])->execute();
            }
            Yii::$app->session->set('active_language', $lang);
        }
        $return = Yii::$app->request->getReferrer();
        if (!isset($return)) {
            $return = Yii::$app->request->getBaseUrl();
        }
        return $this->redirect($return);
    }

    // Trang search tạm khi 2 hệ thống cần login riêng
    public function actionSearch()
    {

        // $tim = Yii::$app->request->post('tim', '');
        // $tim = \yii\helpers\Inflector::slug($tim);
        // $tim = str_replace(['-'], [''], $tim);

        if (1 || USER_ID == 1) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tim = Yii::$app->request->get('q');
            $tim = str_replace(':', 'xxxxyyyyzzzz', $tim);
            $tim = \yii\helpers\Inflector::slug($tim);
            $tim = str_replace(['-'], [''], $tim);
            $tim = str_replace('xxxxyyyyzzzz', ':', $tim);

            if (substr($tim, 0, 2) == 'k:' || substr($tim, 0, 2) == 's:') {
                // Kase, sales
                $tim = substr($tim, 2);
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found'])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim])
                    ->andWhere(['rtype'=>'case']);
            } elseif (substr($tim, 0, 2) == 't:' || substr($tim, 0, 2) == 'b:') {
                // Tour, booking
                $tim = substr($tim, 2);
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found'])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim])
                    ->andWhere(['rtype'=>'tour']);
            } elseif (substr($tim, 0, 2) == 'c:' || substr($tim, 0, 2) == 'y:') {
                // Company
                $tim = substr($tim, 2);
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found'])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim])
                    ->andWhere(['rtype'=>'company']);
            } elseif (substr($tim, 0, 2) == 'v:' || substr($tim, 0, 2) == 'h:') {
                // Venue, hotels
                $tim = substr($tim, 2);
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found'])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim])
                    ->andWhere(['rtype'=>'company']);
            } elseif (substr($tim, 0, 2) == 'p:' || substr($tim, 0, 2) == 'u:') {
                // Person ,user
                $tim = substr($tim, 2);
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found',
                        'image'=>new \yii\db\Expression('IF(rtype="user", (SELECT image FROM persons u WHERE u.id=rid LIMIT 1), "")')
                        ])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim])
                    ->andWhere(['rtype'=>'user']);
            } else {
                $query = Search::find()
                    ->select(['rtype', 'rid', 'found',
                    'image'=>new \yii\db\Expression('IF(rtype="user", (SELECT image FROM persons u WHERE u.id=rid LIMIT 1), "")')
                    ])
                    ->where('LOCATE(:tim, search)!=0', [':tim'=>$tim]);
            }

            $countQuery = clone $query;
            $pagination = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize'=>30,
            ]);

            $found = $query
                ->orderBy('rtype')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            $result = [];
            foreach ($found as $f) {
                if ($f['rtype'] == 'user') {
                    //$r['avatar_url'] = '<i class="fa fa-user text-pink"></i>';
                    $r['url'] = 'https://my.amicatravel.com/users/r/'.$f['rid'];
                    $r['avatar_url'] = '<img style="width:48px; height:48px; float:left; margin-right:10px" class="img-circle" src="'.($f['image'] == '' ? 'https://secure.gravatar.com/avatar/'.md5($f['rid']).'?d=wavatar' : '/timthumb.php?w=100&h=100&zc=1&src='.$f['image']).'">';
                } elseif ($f['rtype'] == 'case') {
                    $r['avatar_url'] = '<i class="fa fa-briefcase text-warning"></i>';
                    $r['url'] = 'https://my.amicatravel.com/cases/r/'.$f['rid'];
                } elseif ($f['rtype'] == 'tour') {
                    $r['avatar_url'] = '<i class="fa fa-car text-success"></i>';
                    $r['url'] = 'https://my.amicatravel.com/tours/r/'.$f['rid'];
                } elseif ($f['rtype'] == 'venue') {
                    $r['avatar_url'] = '<i class="fa fa-hotel text-default"></i>';
                    $r['url'] = 'https://my.amicatravel.com/venues/r/'.$f['rid'];
                } elseif ($f['rtype'] == 'company') {
                    $r['avatar_url'] = '<i class="fa fa-home text-slate"></i>';
                    $r['url'] = 'https://my.amicatravel.com/companies/r/'.$f['rid'];
                } elseif ($f['rtype'] == 'package') {
                    $r['avatar_url'] = '<i class="fa fa-fire text-slate"></i>';
                    $r['url'] = 'https://my.amicatravel.com/packages/r/'.$f['rid'];
                }
                //$r['description'] = Yii::$app->security->generateRandomString();
                $r['id'] = $r['url'];
                $r['found'] = $f['found'];
                if ($f['rtype'] == 'package') {
                    $r['found'] .= ' (package)';
                }
                //$r['avatar_url'] = 'https://my.amicatravel.com/timthumb.php?zc=1&w=100&h=100&src=https://my.amicatravel.com/upload/user-files/1/huan-160801.jpg';
                //$r['forks_count'] = random_int(0, 500);
                //$r['stargazers_count'] = random_int(0, 500);
                //$r['watchers_count'] = random_int(0, 500);
                $result[] = $r;
            }
            return [
                'total_count'=>$pagination->totalCount,
                'incomplete_results'=>false,
                'items'=>$result,
            ];
        } else {
            if (strlen($tim) <= 1) {
                echo '<a class="text-danger" href="/search"><i class="fa fa-ban"></i> Please enter at least 2 characters. Click for advanced search...</a>';
                exit;
            }
        }



//      $sql = 'SELECT rtype, rid, found, IF(rtype=%s, (SELECT image FROM persons u WHERE u.id=rid LIMIT 1), %s) AS image  FROM at_search WHERE LOCATE(%s, search)!=0 ORDER BY rtype LIMIT 20', 'user', '', $tim);
        $sql = 'SELECT rtype, rid, found, IF(rtype="user", (SELECT image FROM persons u WHERE u.id=rid LIMIT 1), "") AS image FROM at_search WHERE LOCATE(:tim, search)!=0 ORDER BY rtype LIMIT 20';
        $found = Yii::$app->db->createCommand($sql, [':tim'=>$tim])->queryAll();
        if ($found) {
            foreach ($found as $f) {
                if ($f['rtype'] == 'user') echo '<a class="td-n" href="/users/r/'.$f['rid'].'"><img style="width:20px; height:20px" src="'.($f['image'] == '' ? 'http://www.gravatar.com/avatar/'.md5($f['rid']).'?d=wavatar' : '/timthumb.php?w=100&h=100&zc=1&src='.$f['image']).'"> '.$f['found'].'</a>';
                if ($f['rtype'] == 'case') echo '<a class="td-n" href="/cases/r/'.$f['rid'].'"><i class="fa fa-briefcase"></i> '.$f['found'].'</a>';
                if ($f['rtype'] == 'tour') echo '<a class="td-n" href="/tours/r/'.$f['rid'].'"><i class="fa fa-car"></i> '.$f['found'].'</a>';
                if ($f['rtype'] == 'venue') echo '<a class="td-n" href="/venues/r/'.$f['rid'].'"><i class="fa fa-map-marker"></i> '.$f['found'].'</a>';
                if ($f['rtype'] == 'company') echo '<a class="td-n" href="/companies/r/'.$f['rid'].'"><i class="fa fa-home"></i> '.$f['found'].'</a>';
                if ($f['rtype'] == 'package') echo '<a class="td-n" href="/packages/r/'.$f['rid'].'"><i class="fa fa-fire"></i> '.$f['found'].' (Package)</a>';
            }
        } else {
            echo '<a class="text-danger" href="/search"><i class="fa fa-ban"></i> No results found. Click for advanced search...</a>';
        }
        //return $this->renderPartial('search');
    }

    // Trang search tạm khi 2 hệ thống cần login riêng
    public function actionRedir($url = '')
    {
        if (strpos($url, '://') !== false) {
            return $this->redirect($url);
        }
        return $this->redirect(DIR);
    }

    public function actionTest()
    {
        $array = [
            [
                'value'=>'<a href="/tours">Pham Hoang Hai</a>',
                'tokens'=>['Pham', 'Hoang', 'Hai'],
            ],
            [
                'value'=>'Kabila Oma Chan',
                'tokens'=>['Chandra', 'Kabila'],
            ],
            [
                'value'=>'Hoang Ngoc Huan',
                'tokens'=>['Hoang', 'Ngoc', 'Huan'],
            ],
        ];
        return json_encode($array);
    }
}
