<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Message;
use common\models\Note;
use common\models\User;
use common\models\Kase;
use common\models\Tour;

class MessageController extends MyController
{
    public function actionIndex($from = 0, $to = 0, $via = 'all', $month = 'all', $title = '') {

        $fromList = [
            '0'=>'Anybody',
            Yii::$app->user->id=>'Me',
        ];
        $toList = [
            '0'=>'Anybody',
            Yii::$app->user->id=>'Me',
        ];
        if (in_array(Yii::$app->user->id, [1,2,3,4,118,695,4432,1351,7756,9881])) {
            $viewAll = true;
            if ($from != 0 && $from != Yii::$app->user->id) {
                $theFromUser = User::find()->where(['id'=>$from])->one();
                if (!$theFromUser) {
                    throw new HttpException(404, 'User not found');
                } 
                $fromList[$from] = $theFromUser['name'];
            }
            if ($to != 0 && $to != Yii::$app->user->id) {
                $theToUser = User::find()->where(['id'=>$to])->one();
                if (!$theToUser) {
                    throw new HttpException(404, 'User not found');
                } 
                $toList[$to] = $theToUser['name'];
            }
        } else {
            $viewAll = false;
        }

        $viaList = [
            'web'=>'IMS note',
            'email'=>'Email note',
            'form'=>'Web form inquiry',
            'ev'=>'Client space',
        ];

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(uo, 1, 7) AS ym FROM at_messages GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        $query = Note::find();

        if (strlen($month) == 7) {
            $query->andWhere('SUBSTRING(at_messages.uo,1,7)=:ym', [':ym'=>$month]);
        }

        if (!$viewAll) {
            if ($from == 0 && $to == 0) {
                // Mac dinh note cua toi
                $from = Yii::$app->user->id;
            } else {
                if ($from == 0) {
                    $to = Yii::$app->user->id;
                } else {
                    $from = Yii::$app->user->id;
                }
            }
        }

        if ($from != 0) {
            $query->andWhere(['from_id'=>$from]);
        }

        if ($to != 0) {
            $query->innerJoinWith([
                'sto'=>function($query) {
                    $query->andWhere(['persons.id'=>Yii::$app->request->get('to')]);
                    return $query;
                },
            ]);
        }

        if (strlen($title) >= 2) {
            $query->andWhere(['like', 'title', $title]);
        }

        if (in_array($via, ['web', 'email', 'form', 'ev'])) {
            $query->andWhere(['via'=>$via]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $theNotes = $query
            ->select('via, file_count, at_messages.id, at_messages.co, at_messages.cb, at_messages.uo, at_messages.ub, at_messages.title, at_messages.from_id, at_messages.rtype, at_messages.rid, at_messages.body')
            ->with([
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'from'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'to'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'files',
                'relatedCase',
                'relatedTour'
                ])
            ->orderBy('uo DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('message_index', [
            'pagination'=>$pagination,
            'from'=>$from,
            'to'=>$to,
            'via'=>$via,
            'title'=>$title,
            'month'=>$month,
            'theNotes'=>$theNotes,
            'monthList'=>$monthList,
            'viaList'=>$viaList,
            'viewAll'=>$viewAll, // if user can view all
            'fromList'=>$fromList,
            'toList'=>$toList,
        ]);
    }

    public function actionC() {

    }

    public function actionR($id = 0)
    {
        $theNote = Note::find()
            ->where(['id'=>$id])
            ->with([
                'updatedBy',
                'from'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'email', 'image']);
                },
                'to'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'email', 'image']);
                },
                'files',
                'replies',
                'replies.updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'email', 'image']);
                },
                'replies.to'=>function($q){
                    return $q->select(['id', 'name'=>'nickname', 'email', 'image']);
                },
            ])
            ->one();

        if (!$theNote) {
            throw new HttpException(404, 'Note not found');
        }

        if ($theNote['n_id'] != 0) {
            return $this->redirect('@web/messages/r/'.$theNote['n_id']);
        }

        // People who will be notified when reply is posted
        $emailList = [$theNote['updatedBy']['email']];
        foreach ($theNote['replies'] as $reply) {
            $emailList[] = $reply['updatedBy']['email'];
        }
        foreach ($theNote['to'] as $to) {
            $emailList[] = $to['email'];
        }
        $emailList = array_unique($emailList);

        if (preg_match_all('/@\[user\-(\d+)\]/', $theNote['body'], $matches)) {
            //\fCore::expose($matches[1]);
            //exit;
            $mentionedPeople = User::find()
                ->select(['id', 'name', 'email', 'image'])
                ->where(['id'=>$matches[1]])
                ->asArray()
                ->all();
        } else {
            $mentionedPeople = [];
        }

        $theMessage = new Message;
        $theMessage->scenario = 'message/r';

        if ($theMessage->load(Yii::$app->request->post()) && $theMessage->validate()) {
            $theMessage->co = NOW;
            $theMessage->cb = USER_ID;
            $theMessage->uo = NOW;
            $theMessage->ub = USER_ID;
            $theMessage->status = 'on';
            $theMessage->via = 'web';
            $theMessage->priority = 'A1';
            $theMessage->from_id = USER_ID;
            $theMessage->m_to = 0;
            $theMessage->title = 'Trả lời: '.$theNote['title'];
            $theMessage->rtype = $theNote['rtype'];
            $theMessage->rid = $theNote['rid'];
            $theMessage->n_id = $theNote['id'];
            if (!$theMessage->save(false)) {
                die('NOTE NOT SAVED');
            }
            // TODO email
            if (!empty($emailList)) {
                $subject = $theMessage['title'];

                $args = [
                    ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
                    ['reply-to', 'msg-'.$theNote->id.'-'.$theNote->cb.'@amicatravel.com'],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ];
                foreach ($emailList as $email) {
                    if ($email != Yii::$app->user->identity->email) {
                        $args[] = ['to', $email];
                    }
                }
                if ($theNote['rtype'] == 'company') {
                    $rType = 'companies/r';
                } else {
                    $rType = $theNote['rtype'].'s/r';
                }
                $this->mgIt(
                    $subject,
                    '//mg/note_added',
                    [
                        'toList'=>[],
                        'theNote'=>$theMessage,
                        'relUrl'=>'https://my.amicatravel.com/'.$rType.'/'.$theNote['rid'],
                        'body'=>$theMessage['body'],
                    ],
                    $args
                );
            }
            // Return
            return $this->redirect('@web/messages/r/'.$theNote['id']);
        }

        return $this->render('message_r', [
            'theNote'=>$theNote,
            'mentionedPeople'=>$mentionedPeople,
            'theMessage'=>$theMessage,
            'emailList'=>$emailList,
        ]);
    }

    public function actionU($id = 0)
    {
        if (USER_ID != 1) {
            return $this->redirect('@web/n/u/'.$id);
        }
        
        $theNote = Message::find()
            ->where(['id'=>$id])
            ->with(['files', 'updatedBy'])
            ->one();
        if (!$theNote) {
            throw new HttpException(404, 'Message not found');
        }

        $theNote->scenario = 'message/u';

        if ($theNote->load(Yii::$app->request->post()) && $theNote->validate()) {
            if ($theNote->save(false)) {
                return $this->redirect('@web/notes/r/'.$theNote['id']);
            }
        }

        return $this->render('message_u', [
            'theNote'=>$theNote,
        ]);
    }

    public function actionD($id = 0)
    {
        $theNote = Note::find()
            ->where(['id'=>$id])
            ->with('files')
            ->one();
        if (!$theNote) {
            throw new HttpException(404, 'Note not found');
        }

        
        if (
            // Neu la Thu, Chinh, Nguyen & venue
            (!in_array(USER_ID, [9198, 8, 28722]) || $theNote['rtype'] != 'venue') &&
            (!in_array(USER_ID, [9198, 8, 28722]) || $theNote['rtype'] != 'company') &&
            // Khong co quyen
            !in_array(USER_ID, [1, $theNote['cb'], $theNote['ub']])
            ) {
            throw new HttpException(404, 'Access denied');
        }

        if (isset($_POST['confirm']) && $_POST['confirm'] == 'delete') {
            foreach ($theNote['files'] as $file) {
                $filePath = Yii::getAlias('@webroot').'/user-files/'.substr($file['uo'], 0, 7).'/file-'.$file['ub'].'-'.$file['id'].'-'.$file['uid'];
                @unlink($filePath);
            }
            Yii::$app->db->createCommand()->delete('at_files', ['n_id'=>$theNote['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_tasks', ['n_id'=>$theNote['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_message_to', ['message_id'=>$theNote['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_messages', ['n_id'=>$theNote['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_messages', ['id'=>$theNote['id']])->execute();

            if ($theNote['rtype'] == 'company') {
                $theNote['rtype'] = 'compani';
            }
            return $this->redirect(DIR.$theNote['rtype'].'s/r/'.$theNote['rid']);
        }

        return $this->render('message_d', [
            'theNote'=>$theNote
        ]);
    }
}
