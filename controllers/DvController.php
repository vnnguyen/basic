<?

namespace app\controllers;

use common\models\Dv;
use common\models\Cp;
use common\models\Venue;
use common\models\Company;
use common\models\Tour;
use common\models\Destination;

use app\models\DvTestForm;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class DvController extends MyController
{

    public function actionAjax()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(403, 'Access denied.');
        }

        if (isset($_POST['name']) && $_POST['name'] == 'abbr') {
            $theVenue = Venue::findOne($_POST['pk']);
            $theVenue->scenario = 'venue/u';
            $theVenue->abbr = $_POST['value'];
            if ($theVenue->save()) {
                exit;
            }
            throw new HttpException(403, 'Error saving data.');
        }

        throw new HttpException(403, 'Access denied.');
    }

    // Copy thong tin dv tu dong
    public function actionDo($stype = 'hotel') {
        $theVenues = Venue::find()
            ->select(['id', 'name'])
            ->where(['stype'=>'hotel'])
            ->with([
                'cp'=>function($q) {
                    return $q->select(['id', 'name', 'venue_id', 'grouping']);
                }
                ])
            ->asArray()
            ->all();
        return $this->render('dv_do', [
            'theVenues'=>$theVenues,
        ]);
    }

    public function actionIndex($tk = '', $venue = '', $form = 'compact', $search = '', $type = '', $name = '', $status = '') {
        $query = Dv::find();

        if ($form == 'compact') {
            if ($search != '') {
                $tokenArray = ['$', '%', '+', '=', '@', '#', '!', '?'];
                $parts = explode(' ', $search);
                $lastPart = '';
                $params = [];
                foreach ($parts as $part) {
                    $str = trim(strtolower($part));
                    if (in_array($str, $tokenArray)) {
                        $lastPart = $str;
                    } else {
                        if ($str != '') {
                            if (in_array($lastPart, $tokenArray)) {
                                $str = $lastPart.$str;
                            }
                            $params[] = $str;
                            $lastPart = '';
                        }
                    }
                }

                //\fCore::expose($params);
                //exit;

                foreach ($params as $param) {
                    if (substr($param, 0, 1) == '@') {
                        $s = substr($param, 1);
                        if ($s != '') {
                            $query->andWhere('LOCATE(:s,search_loc)!=0', [':s'=>$s]);
                        }
                    } else {
                        $t = substr($param, 0, 1);
                        $s = substr($param, 1);
                        $query->andWhere(['stype'=>$t]);
                        if ($s != '') {
                            $query->andWhere('LOCATE(:s,search)!=0', [':s'=>$s]);
                        }
                    }
                }


                $search = implode(' ', $params);
            }
        } elseif ($form == 'full') {
            if (strlen($venue) != '') {
                $venueIdList = Venue::find()
                    ->select(['id'])
                    ->where(['like', 'name', $venue])
                    ->limit(100)
                    ->asArray()
                    ->column();
                if (!empty($venueIdList)) {
                    $query->andWhere(['venue_id'=>$venueIdList]);
                }
            }
            if ($type != '') {
                $query->andWhere(['stype'=>$type]);
            }
            if ($name != '') {
                $query->andWhere(['like', 'name', $name]);
            }
        }

        if ($status == 'deleted') {
            $query->andWhere(['status'=>'deleted']);
        } else {
            $query->andWhere('status!="deleted"');
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
        ]);

        $theDvx = $query
            ->with([
                'cp',
                //'cp.viaCompany',
                'venue',
                'supplier',
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('updated_dt DESC')
            ->asArray()
            ->all();

        return $this->render('dv_index', [
            'pagination'=>$pagination,
            'theDvx'=>$theDvx,
            'form'=>$form,
            'search'=>$search,
            'type'=>$type,
            'name'=>$name,
            'venue'=>$venue,
            'tk'=>$tk,
        ]);
    }

    // Hotels: list all hotels for Chinh/Thu to quickly edit
    public function actionChecklist($type = 'hotel', $dest = 0, $name = '', $search = '') {
        $query = Venue::find()
            ->where(['stype'=>$type]);

        if ($dest != 0) {
            $query->andWhere(['destination_id'=>(int)$dest]);
        }
        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }
        if ($search != '') {
            $query->andWhere(['like', 'search', $search]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theVenues = $query
            ->select(['id', 'abbr', 'destination_id', 'name', 'search', 'supplier_id'])
            ->andWhere('status!="deleted"')
            ->with([
                'destination',
                'destination.country',
                'dvc',
                'cpt'=>function($q) {
                    return $q->select(['dvtour_id', 'venue_id', 'dvtour_day'])->where('YEAR(dvtour_day) IN (2016,2017)');
                },
                'supplier'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('abbr, destination_id, name')
            ->asArray()
            ->all();

        $destList = Destination::find()
            ->select(['id', 'name_en', 'country_code'])
            ->asArray()
            ->all();

        return $this->render('dv_checklist', [
            'pagination'=>$pagination,
            'theVenues'=>$theVenues,
            'type'=>$type,
            'dest'=>$dest,
            'destList'=>$destList,
            'name'=>$name,
            'search'=>$search,
        ]);
    }

    public function actionC($venue_id = 0, $supplier_id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDv = new Dv;
        $theDv->scenario = 'dv/c';
        $theDv->is_dependent = 'no';
        $theDv->conds = 'b,';
        $theDv->maxpax = 1;

        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->status = 'on';
            $theDv->created_dt = NOW;
            $theDv->created_by = USER_ID;
            $theDv->updated_dt = NOW;
            $theDv->updated_by = USER_ID;
            $theDv->venue_id = $venue_id;
            $theDv->save(false);
            if ($venue_id != 0) {
                return $this->redirect('/venues/r/'.$venue_id);
            } else {
                return $this->redirect('/dv');
            }
        }

        return $this->render('dv_u', [
            'theDv'=>$theDv,
        ]);
    }

    public function actionCx()
    {
        if (!in_array(USER_ID, [1])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDv = new Dv;
        $theDv->scenario = 'dv/c';
        $theDv->is_dependent = 'no';
        $theDv->booking_conds = 'b,';

        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->status = 'on';
            $theDv->created_dt = NOW;
            $theDv->created_by = USER_ID;
            $theDv->updated_dt = NOW;
            $theDv->updated_by = USER_ID;
            $theDv->save(false);
            return $this->redirect('@web/dv');
        }

        $venueList = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('dv_u', [
            'theDv'=>$theDv,
            'venueList'=>$venueList,
            'companyList'=>$companyList,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDv = Dv::find()
            ->where(['id'=>$id])
            ->with([
                'venue',
                'supplier',
                'cp',
                //'cp.viaCompany',
                ])
            ->one();

        if (!$theDv) {
            throw new HttpException(404, 'Dv not found');           
        }

        return $this->render('dv_r', [
            'theDv'=>$theDv,
        ]);
    }

    public function actionUx($id = 0)
    {
        if (!in_array(USER_ID, [1])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDv = Dv::findOne($id);
        if (!$theDv) {
            throw new HttpException(404, 'Dv not found.');
        }

        $theDv->scenario = 'dv/u';

        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->updated_dt = NOW;
            $theDv->updated_by = USER_ID;
            $theDv->save(false);
            return $this->redirect('@web/dv');
        }

        $venueList = Venue::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        $companyList = Company::find()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('dv_u', [
            'theDv'=>$theDv,
            'venueList'=>$venueList,
            'companyList'=>$companyList,
        ]);
    }

    public function actionU($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDv = Dv::findOne($id);
        if (!$theDv) {
            throw new HttpException(404, 'Dv not found.');
        }

        $theDv->scenario = 'dv/u';

        if ($theDv->load(Yii::$app->request->post()) && $theDv->validate()) {
            $theDv->updated_dt = NOW;
            $theDv->updated_by = USER_ID;
            $data1 = implode(';', [$theDv->type1, $theDv->type2, $theDv->type3, $theDv->type4, $theDv->type5]);
            $data2 = implode(';', [$theDv->name1, $theDv->name2, $theDv->name3, $theDv->name4, $theDv->name5]);
            $data = $data1.'|'.$data2;
            $theDv->data = $data;
            $theDv->save(false);
            if ($theDv['venue_id'] != 0) {
                return $this->redirect('/venues/r/'.$theDv['venue_id']);
            } else {
                return $this->redirect('/dv');
            }
        }

        return $this->render('dv_u', [
            'theDv'=>$theDv,
        ]);
    }

    // Delete redundant DBL|TWN
    public function actionP($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        $theDv = Dv::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        if (!$theDv) {
            throw new HttpException(404, 'Dv not found');
        }

        if (substr($theDv['name'], -4) == ' SGL') {
            $roomtype = trim(substr($theDv['name'], 0, strlen($theDv['name']) - 4));
            $theDvx = Dv::find()
                ->where(['venue_id'=>$theDv['venue_id']])
                ->andWhere('id!='.$id)
                ->asArray()
                ->all();
            $trail = ' {*SGL';
            $dbl = false;
            $twn = false;
            foreach ($theDvx as $dv) {
                if ($dv['name'] == $roomtype.' DBL') {
                    $dbl = true;
                }
                if ($dv['name'] == $roomtype.' TWN') {
                    $twn = true;
                }
            }
            if ($dbl) {
                $trail .= '|DBL';
            }
            if ($twn) {
                $trail .= '|TWN';
            }
            $trail .= '}';
            if ($dbl || $twn) {
                $theDv['name'] = $roomtype.$trail;
                foreach ($theDvx as $dv) {
                    if ($dv['name'] == $roomtype.' DBL' || $dv['name'] == $roomtype.' TWN') {
                        Yii::$app->db
                            ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$dv['id']])
                            ->execute();
                    }
                }
                Yii::$app->db
                    ->createCommand('UPDATE dv SET name=:n WHERE id=:id LIMIT 1', [':n'=>$theDv['name'], 'id'=>$id])
                    ->execute();
            }
            return $this->redirect('/venues/r/'.$theDv['venue_id']);
        }
    }

    // Changs status to deleted
    public function actionD($id = 0)
    {
        if (!in_array(USER_ID, [1, 8, 9198])) {
            throw new HttpException(403, 'Access denied.');
        }

        if (Yii::$app->request->isAjax) {
            $theDv = Dv::find()
                ->where(['id'=>$id])
                ->asArray()
                ->one();
            if (!$theDv) {
                throw new HttpException(404, 'Dv not found');
            }
            Yii::$app->db
                ->createCommand('UPDATE dv SET status="deleted" WHERE id=:id LIMIT 1', ['id'=>$id])
                ->execute();
        }

        exit;

        $theDv = Dv::find()
            ->where(['id'=>$id])
            ->with([
                'cpt'=>function($q) {
                    return $q->limit(10);
                }
                ])
            ->asArray()
            ->one();

        if (!$theDv) {
            throw new HttpException(404, 'Dv not found');
        }

        if (!empty($theDv['cpt'])) {
            throw new HttpException(403, 'Related bookings found. You need to delete them first.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
            // Delete related cpg
            Yii::$app->db->createCommand()
                ->delete('dv', ['id'=>$id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('cp', ['dv_id'=>$id])
                ->execute();
            if ($theDv['venue_id'] != 0) {
                return $this->redirect('@web/venues/r/'.$theDv['venue_id']);
            } else {
                return $this->redirect('@web/dv');
            }
        }

        return $this->render('dv_d', [
            'theDv'=>$theDv,
        ]);
    }

    public function actionHelp($page = '00')
    {
        return $this->render('dv_help', [
            'page'=>$page,
        ]);
    }

    // Test form nhap du lieu cp
    public function actionTest()
    {
        $theForm = new DvTestForm;
        return $this->render('dv_test', [
            'theForm'=>$theForm,
        ]);
    }
}
