<?
namespace app\controllers;

use app\models\Note;
use app\models\Search;
use app\models\User;
use app\models\User2;
use app\models\Message;

use Yii;
use yii\data\Pagination;
use yii\web\Response;


class DefaultController extends MyController
{
    public function actionIndex()
    {

        return $this->render('home', [
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
