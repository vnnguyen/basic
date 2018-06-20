<?

namespace app\controllers;

use common\models\Incident;
use common\models\Cpx;
use common\models\Venue;
use common\models\Company;
use common\models\Product;
use common\models\Tour;
use common\models\User2;
use common\models\Note;
use common\models\Message;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;

class IncidentController extends MyController
{
    public function actionIndex($year = 0, $month = 0, $type = 0, $severity = 0, $status = 0, $name = '') {
        $query = Incident::find();

        if ($year != 0) {
            $query->andWhere('YEAR(incident_date)=:year', [':year'=>$year]);
        }
        if ($month != 0) {
            $query->andWhere('MONTH(incident_date)=:month', [':month'=>$month]);
        }
        if ($type != 0) {
            $query->andWhere(['stype'=>$type]);
        }
        if ($severity != 0) {
            $query->andWhere(['severity'=>$severity]);
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

        $theIncidents = $query
            ->with([
                'tour'=>function($q) {
                    return $q->select(['id', 'op_code', 'op_name']);
                },
                'owner'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
                ])
            ->orderBy('incident_date DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('incident_index', [
            'pagination'=>$pagination,
            'theIncidents'=>$theIncidents,
            'year'=>$year,
            'month'=>$month,
            'type'=>$type,
            'severity'=>$severity,
            'status'=>$status,
            'name'=>$name,
        ]);
    }

    public function actionC($tour_id = 0)
    {
        $theIncident = new Incident;
        $theIncident->incident_date = date('Y-m-d');
        $theIncident->status = 1;
        $theIncident->owner_id = [USER_ID];

        if ($tour_id != 0) {
            $theTour = Product::find()
                ->where(['op_status'=>'op', 'id'=>$tour_id])
                ->asArray()
                ->one();
            if ($theTour) {
                $theIncident->tour_code = $theTour['op_code'];
            }
        }

        if ($theIncident->load(Yii::$app->request->post()) && $theIncident->validate()) {
            $theTour = Product::find()
                ->where(['op_status'=>'op', 'op_code'=>$theIncident->tour_code])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }


            // Find people in charge
            $userIdList = array_merge([$theIncident['owner_id']], $theIncident['owners']);
            $userList = User2::find()
                ->select(['fname', 'lname', 'email'])
                ->where(['id'=>$userIdList, 'status'=>'on'])
                ->asArray()
                ->all();

            $theIncident->tour_id = $theTour['id'];

            $theIncident->created_dt = NOW;
            $theIncident->created_by = USER_ID;
            $theIncident->updated_dt = NOW;
            $theIncident->updated_by = USER_ID;
            $theIncident->save(false);

            $args = [
                ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                ['reply-to', 'msg-incident'.$theIncident->id.'-'.USER_ID.'@amicatravel.com'],
                // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];
            foreach ($userList as $user) {
                $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
            }
            // $this->mgIt(
            //     '#incident '.$theIncident['name'],
            //     '//mg/incident_added',
            //     [
            //         'theIncident'=>$theIncident,
            //     ],
            //     $args
            // );

            return $this->redirect('@web/incidents/r/'.$theIncident->id);
        }

        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        return $this->render('incident_u', [
            'theIncident'=>$theIncident,
            'staffList'=>$staffList,
        ]);
    }
    public function actionI_delete($id = 0)
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
    public function actionI_update($id = 0, $body)
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

    public function actionI_report()
    {
        $dt = Yii::$app->request->get('range', '');
        if ($dt != '') {
            $range = explode('-', $dt);
            if (count($range) == 2) {
                $getFrom = $range[0];
                $getTo = $range[1];
               
                $incidents = Incident::find()
                ->select(['stype', 'count(*) AS cnt'])
                ->where('DATE(created_dt) >= DATE(:getFrom) AND DATE(created_dt) <= DATE(:getTo)', [':getFrom' => $getFrom, ':getTo' => $getTo])
                ->groupBy('stype')
                ->asArray()
                ->all();
            }
        }
        return $this->render('i_report',[
            'result' => (isset($incidents)) ? $incidents : null,
        ]);
    }

    public function actionR($id = 0)
    {
        $theIncident = Incident::find()
            ->select(['*'])
            ->where(['incidents.id'=>$id])
            ->with([
                'tour',
                'complaint' => function($q){
                    return $q->select(['name', 'status']);
                },
            ])
            ->one();
        if (!$theIncident) {
            throw new HttpException(404, Yii::t('incident', 'Incident not found'));
        }
        $userList = implode(',', $theIncident['owners']);
        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();
        $users = ArrayHelper::map($staffList, 'id', 'name');

        $query = Message::find()
            ->join('INNER JOIN', 'at_relations', 'at_messages.id = at_relations.oid')
            ->where(['at_messages.rid' => $theIncident['tour_id'], 'at_relations.rtype' => 'incident', 'at_relations.rid' => $theIncident['id']])
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
            $theMessage->rid = $theIncident['tour']['id'];
            $theMessage->body = $_POST['message'];
            // $theMessage->n_id = 0;
            if (!$theMessage->save(false)) {
                die('NOTE NOT SAVED');
            }
            // if (!empty($emailList)) {
            //     $subject = $theMessage['title'];

            //     $args = [
            //         ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
            //         ['reply-to', 'msg-'.$theNote->id.'-'.$theNote->cb.'@amicatravel.com'],
            //         ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            //     ];
            //     foreach ($emailList as $email) {
            //         if ($email != Yii::$app->user->identity->email) {
            //             $args[] = ['to', $email];
            //         }
            //     }
            //     if ($theNote['rtype'] == 'company') {
            //         $rType = 'companies/r';
            //     } else {
            //         $rType = $theNote['rtype'].'s/r';
            //     }
            //     $this->mgIt(
            //         $subject,
            //         '//mg/note_added',
            //         [
            //             'toList'=>[],
            //             'theNote'=>$theMessage,
            //             'relUrl'=>'https://my.amicatravel.com/'.$rType.'/'.$theNote['rid'],
            //             'body'=>$theMessage['body'],
            //         ],
            //         $args
            //     );
            // }
            // Return
             Yii::$app->db->createCommand()
                ->insert('at_relations', [
                    'otype'=>'message',
                    'oid' => $theMessage->id,
                    'rtype' => 'incident',
                    'rid' => $theIncident['id']
                ])
                ->execute();
            return $this->redirect('@web/incidents/r/'.$theIncident['id']);

        }

        return $this->render('incident_r', [
            'theIncident'=>$theIncident,
            'staffList' => $users,
            'messages' => $messages,
        ]);
    }

    public function actionU($id = 0)
    {
        $theIncident = Incident::findOne($id);
        if (!$theIncident) {
            throw new HttpException(404, 'Incident not found.');
        }

        // if (!in_array(USER_ID, [1, 118, 29296, $theIncident['created_by'], $theIncident['updated_by']])) {
        //     throw new HttpException(403, 'Access denied.');
        // }

        $theTour = Product::find()
            ->where(['op_status'=>'op', 'id'=>$theIncident->tour_id])
            ->asArray()
            ->one();
        if ($theTour) {
            $theIncident->tour_code = $theTour['op_code'];
        }

        if ($theIncident->load(Yii::$app->request->post()) && $theIncident->validate()) {

            $theTour = Product::find()
                ->where(['op_status'=>'op', 'op_code'=>$theIncident->tour_code])
                ->asArray()
                ->one();
            if (!$theTour) {
                throw new HttpException(404, 'Tour not found.');
            }

            // Find people in charge
            $userIdList = array_merge([$theIncident['owner_id']], $theIncident['owners']);
            $userList = User2::find()
                ->select(['fname', 'lname', 'email'])
                ->where(['id'=>$userIdList, 'status'=>'on'])
                ->asArray()
                ->all();

            $theIncident->tour_id = $theTour['id'];
            $theIncident->updated_dt = NOW;
            $theIncident->updated_by = USER_ID;
            if (!$theIncident->save(false)) {
                die('Incident Not Saved');
            } 

            $args = [
                ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                ['reply-to', 'msg-'.$theIncident->id.'-'.USER_ID.'@amicatravel.com'],
                ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
            ];
            foreach ($userList as $user) {
                $args[] = ['to', $user['email'], $user['lname'], $user['fname']];
            }
            // $this->mgIt(
            //     'Incident has been updated: '.$theIncident['name'],
            //     '//mg/incident_added',
            //     [
            //         'theIncident'=>$theIncident,
            //     ],
            //     $args
            // );
            return $this->redirect('@web/incidents/r/'.$theIncident['id']);
        }

        $staffList = User2::find()
            ->select(['id', 'name'=>'nickname'])
            ->where(['status'=>'on'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        return $this->render('incident_u', [
            'theIncident'=>$theIncident,
            'staffList'=>$staffList,
        ]);
    }

    public function actionD($id = 0)
    {
        $theIncident = Incident::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theIncident) {
            throw new HttpException(404, 'Cp not found');
        }

        if (!in_array(Yii::$app->user->id, [1, 9, 7766, 9198])) {
            throw new HttpException(403, 'Access denied');
        }

        if ($theIncident['id']) {
            throw new HttpException(403, 'Related bookings found. You need to delete them first.');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            // Delete related cpg
            Yii::$app->db->createCommand()
                ->delete('incidents', ['id'=>$id])
                ->execute();
            return $this->redirect('@web/cp');
        }

        return $this->render('incident_d', [
            'theIncident'=>$theIncident
        ]);
    }
}
