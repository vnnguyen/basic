<?php

namespace app\controllers;

use common\models\Incident;
use common\models\Cpx;
use common\models\Venue;
use common\models\Company;
use common\models\Product;
use common\models\Tour;
use common\models\User2;
use common\models\Message;
use app\models\Complaint;
use common\models\Booking;
use common\models\Meta;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class ComplaintController extends MyController
{
    public function actionIndex($year = 0, $month = 0, $type = 0,$status = 0, $name = '') {
        $query = Complaint::find();

        if ($year != 0) {
            $query->andWhere('YEAR(complaint_date)=:year', [':year'=>$year]);
        }
        if ($month != 0) {
            $query->andWhere('MONTH(complaint_date)=:month', [':month'=>$month]);
        }
        if ($type != 0) {
            $query->andWhere(['stype'=>$type]);
        }
        if ($status != 0) {
            $query->andWhere(['status'=>$status]);
        }
        if (trim($name) != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>50,
        ]);

        $theComplaints = $query
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'op_code', 'op_name']);
                },
                'owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
                ])
            ->orderBy('complaint_date DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('index', [
            'pagination'=>$pagination,
            'theComplaints'=>$theComplaints,
            'year'=>$year,
            'month'=>$month,
            'type'=>$type,
            'status'=>$status,
            'name'=>$name,
        ]);
    }
    public function actionC($tour_id = 0, $incident = 0)
    {
        $theComplaint = new Complaint;
        $theComplaint->complaint_date = date('Y-m-d');
        $theComplaint->status = 1;
        $theComplaint->owner_id = [USER_ID];


        if ($tour_id != 0) {
            $theTour = Product::find()
            	->with([
        			'pax' => function($q){
        				return $q->select(['id', 'name', 'tour_id']);
        			}
        		])
                ->where(['op_status'=>'op', 'id'=>$tour_id])
                ->asArray()
                ->one();
            if ($theTour) {
                $theComplaint->tour_code = $theTour['op_code'];
                $theComplaint->tour_id = $theTour['id'];
            }
        }
        if ($incident > 0) {
        	$theIncident = Incident::find()
        		->with(['tour'])
        		->where(['id' => $incident])
        		->asArray()->one();
        	if ($theIncident) {
        		$theComplaint->incident_id = $theIncident['id'];
        		$theComplaint->tour_code = $theIncident['tour']['op_code'];
        		$theComplaint->tour_id = $theIncident['tour']['id'];
	        }
        }
        if ($theComplaint->tour_id > 0) {
        	$theTour = Product::find()
            	->with([
        			'pax' => function($q){
        				return $q->select(['id', 'name', 'tour_id']);
        			}
        		])
                ->where(['id'=> $theComplaint->tour_id])
                ->asArray()
                ->one();
        }
        if ($theComplaint['owners'] == '') {
        	$theComplaint['owners'] = [];
        }
        if ($theComplaint->load(Yii::$app->request->post()) && $theComplaint->validate()) {
            $theTour = Product::find()
                ->where(['op_status'=>'op', 'op_code'=>$theComplaint->tour_code])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }


            // Find people in charge
            $userIdList = array_merge([$theComplaint['owner_id']], $theComplaint['owners']);
            $userList = User2::find()
                ->select(['fname', 'lname', 'email'])
                ->where(['id'=>$userIdList, 'status'=>'on'])
                ->asArray()
                ->all();

            $theComplaint->tour_id = $theTour['id'];

            $theComplaint->created_dt = NOW;
            $theComplaint->created_by = USER_ID;
            $theComplaint->updated_dt = NOW;
            $theComplaint->updated_by = USER_ID;
            $theComplaint->incident_id = ($theComplaint->incident_id > 0) ? $theComplaint->incident_id: 0;
            $theComplaint->complaint_user = ($theComplaint->complaint_user > 0) ? $theComplaint->complaint_user: 0;
            if (!$theComplaint->save(false)) {
            	var_dump($theComplaint->errors);die;
            }
            $args = [
                ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                ['reply-to', 'msg-complaint'.$theComplaint->id.'-'.USER_ID.'@amicatravel.com'],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];
            foreach ($userList as $user) {
                // $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
            }
            // $this->mgIt(
            //     '#incident '.$theComplaint['name'],
            //     '//mg/incident_added',
            //     [
            //         '$theComplaint'=>$theComplaint,
            //     ],
            //     $args
            // );

            return $this->redirect('@web/complaints/u/'.$theComplaint->id);
        }
        $incidentList = Incident::find()
        	->select(['id', 'name']);
        if ($theComplaint['tour_id'] > 0) {
        	$incidentList = $incidentList->where(['tour_id' => $theComplaint['tour_id']]);
        }
        $incidentList = $incidentList ->asArray() ->all();
        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();
        return $this->render('complaint_u', [
            'theComplaint'=>$theComplaint,
            'staffList'=>$staffList,
            'incidentList' => $incidentList,
            'theTour' => (isset($theTour)) ? $theTour : null,
        ]);
    }
    public function actionDatas($tour_code = '')
    {
    	if (Yii::$app->request->isAjax) {
    		$theTour = Product::find()
    			->with([
        			'pax' => function($q){
        				return $q->select(['id', 'name', 'tour_id']);
        			},
        			'incidents' => function($q){
        				return $q->select(['id', 'name', 'tour_id']);
        			}
        		])
                ->where(['op_code'=>$tour_code])
                ->asArray()
                ->one();
            if (!$theTour) {
                return json_encode(['err' => 'The tour non found!!!!']);
            }
            return json_encode([
            		'complaintUsers' => $theTour['pax'],
            		'incidents' => $theTour['incidents']
            	]);
    	}
    }

    public function actionR($id = 0)
     {
        $theComplaint = Complaint::find()
            ->where(['id'=>$id])
            ->with(['tour'])
            ->one();
        if (!$theComplaint) {
            throw new HttpException(404, Yii::t('complaint', 'Complaint not found'));
        }
        if (!empty($theComplaint['owners'])) {
        	$userList = implode(',', $theComplaint['owners']);
        } else {$userList = -1;}
        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->andWhere('id IN('.$theComplaint['owner_id'].','.$userList.')')
            ->orderBy('lname, fname')
            ->asArray()
            ->all();
        $users = ArrayHelper::map($staffList, 'id', 'name');
        $query = Message::find()
            ->join('INNER JOIN', 'at_relations', 'at_messages.id = at_relations.oid')
            ->where(['at_messages.rid' => $theComplaint['tour_id'], 'at_relations.rtype' => 'complaint', 'at_relations.rid' => $theComplaint['id']])
            ->orderBy('co');
        $messages = $query->asArray()->all();
        if (isset($_POST['save_message'])) {
            $theMessage = new Message;
            $theMessage->scenario = 'message/c';
            $theMessage->co = NOW;
            $theMessage->cb = USER_ID;
            $theMessage->uo = NOW;
            $theMessage->ub = USER_ID;
            $theMessage->status = 'on';
            $theMessage->via = 'web';
            $theMessage->priority = 'A1';
            $theMessage->from_id = USER_ID;
            $theMessage->m_to = 0;
            $theMessage->title = $users[USER_ID];
            $theMessage->rtype = 'tour';
            $theMessage->rid = $theComplaint['tour']['id'];
            $theMessage->body = $_POST['message'];
            // $theMessage->n_id = 0;
            if (!$theMessage->save(false)) {
                die('NOTE NOT SAVED');
            }
             Yii::$app->db->createCommand()
                ->insert('at_relations', [
                    'otype'=>'message',
                    'oid' => $theMessage->id,
                    'rtype' => 'complaint',
                    'rid' => $theComplaint['id']
                ])
                ->execute();
            return $this->redirect('@web/complaints/r/'.$theComplaint['id']);

        }

        return $this->render('complaint_r', [
            'theComplaint'=>$theComplaint,
            'staffList' => $users,
            'messages' => $messages,
        ]);
    }
    public function actionC_delete($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            $theMessage = Message::findOne($id);
            if (!$theMessage) {
                die('Message not found');
            }
            if ($theMessage['cb'] != USER_ID) {
                die('Deny access!');
            }
            return $theMessage->delete();
        }
    }
    public function actionC_update($id = 0, $body)
    {
        if (Yii::$app->request->isAjax) {
            $theMessage = Message::findOne($id);
            if (!$theMessage) {
                die('Message not found');
            }
            if ($theMessage['cb'] != USER_ID) {
                die('Deny access!');
            }
            $theMessage->uo = NOW;
            $theMessage->ub = USER_ID;
            $theMessage->body = $body;
            if (!$theMessage->save(false)) {
                return json_encode(['err' => 'Message not saved']);
            }

            return json_encode(ArrayHelper::toArray($theMessage));
        }
    }

    public function actionU($id = 0)
    {
        $theComplaint = Complaint::findOne($id);
        if (!$theComplaint) {
            throw new HttpException(404, 'Complaint not found.');
        }

        // if (!in_array(USER_ID, [1, 118, 29296, $theComplaint['created_by'], $theComplaint['updated_by']])) {
        //     throw new HttpException(403, 'Access denied.');
        // }


        $theTour = Product::find()
        	->with([
    			'pax' => function($q){
    				return $q->select(['id', 'name', 'tour_id']);
    			}
        	])
            ->where(['id'=>$theComplaint['tour_id']])
            ->asArray()
            ->one();
        if (!$theTour) {
            throw new HttpException(404, 'Tour not found.');
        }
        $theComplaint->tour_code = $theTour['op_code'];
        if ($theComplaint->load(Yii::$app->request->post()) && $theComplaint->validate()) {

            $theTour = Product::find()
                ->where(['op_status'=>'op', 'op_code'=>$theComplaint->tour_code])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }
           	if ($theComplaint['owners'] == '') {
           		$theComplaint['owners'] = [];
           	}
            // Find people in charge
            $userIdList = array_merge([$theComplaint['owner_id']], $theComplaint['owners']);
            $userList = User2::find()
                ->select(['fname', 'lname', 'email'])
                ->where(['id'=>$userIdList, 'status'=>'on'])
                ->asArray()
                ->all();

            $theComplaint->tour_id = $theTour['id'];

            $theComplaint->updated_dt = NOW;
            $theComplaint->updated_by = USER_ID;
            $theComplaint->incident_id = ($theComplaint->incident_id != '') ? $theComplaint->incident_id: 0;
            $theComplaint->complaint_user = ($theComplaint->complaint_user != '') ? $theComplaint->complaint_user: 0;
            $theComplaint->save(false);

            $args = [
                ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                ['reply-to', 'msg-'.$theComplaint->id.'-'.USER_ID.'@amicatravel.com'],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];
            foreach ($userList as $user) {
                $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
            }
            // $this->mgIt(
            //     'Complaint has been updated: '.$theComplaint['name'],
            //     '//mg/incident_added',
            //     [
            //         'theComplaint'=>$theComplaint,
            //     ],
            //     $args
            // );
            return $this->redirect('@web/complaints');
        }
        $incidentList = Incident::find()
        	->select(['id', 'name'])->where(['tour_id' => $theComplaint['tour_id']])->asArray() ->all();
        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        return $this->render('complaint_u', [
            'theComplaint'=>$theComplaint,
            'staffList'=>$staffList,
            'incidentList' => $incidentList,
            'theTour' => $theTour
        ]);
    }

    public function actionD($id = 0)
    {
        $theComplaint = Complaint::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theComplaint) {
            throw new HttpException(404, 'Cp not found');
        }

        // if (!in_array(Yii::$app->user->id, [1, 9, 7766, 9198])) {
        //     throw new HttpException(403, 'Access denied');
        // }

        if ($theComplaint['id']) {
            throw new HttpException(403, 'Related bookings found. You need to delete them first.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete related cpg
            Yii::$app->db->createCommand()
                ->delete('complaint', ['id'=>$id])
                ->execute();
            return $this->redirect('@web/cp');
        }

        return $this->render('complaint_d', [
            'theComplaint'=>$theComplaint
        ]);
    }
}
