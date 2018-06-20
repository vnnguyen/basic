<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\Response;

use common\models\Task;
use common\models\User;


class TaskController extends MyController
{
    public function actionAjax($action = '', $task_id = 0, $rtype = '', $rid = 0)
    {
        if (!Yii::$app->request->isAjax) {
            //throw new HttpException(403, 'Ajax only');
        }

        if ($action == 'load_task') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $theTask = Task::find()
                ->where(['id'=>$task_id])
                ->with([
                    'assignees'=>function($q) {
                        return $q->select(['id']);
                    }
                    ])
                ->asArray()
                ->one();
            if (!$theTask) {
                throw new HttpException(404, 'Task not found.');
            }
            if (!in_array(USER_ID, [1, $theTask['cb'], $theTask['ub']])) {
                throw new HttpException(403, 'Access denied.');
            }

            $time_fuzzy = 'e';
            $date = substr($theTask['due_dt'], 0, 10);
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
            $who = [];
            foreach ($theTask['assignees'] as $user) {
                $who[] = $user['id'];
            }
            $response = [
                'description'=>$theTask['description'],
                'is_priority'=>$theTask['is_priority'],
                'date'=>$date,
                'time_fuzzy'=>$time_fuzzy,
                'time'=>$time,
                'mins'=>$theTask['mins'],
                'who'=>$who,
            ];
            return $response;
        }

        if ($action == 'update_task') {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($task_id == 0) {
                // New task
                $theTask = new Task;
                $theTask->co = NOW;
                $theTask->uo = NOW;
                $theTask->cb = USER_ID;
                $theTask->ub = USER_ID;
                $theTask->status = 'on';
                $theTask->rtype = $rtype;
                $theTask->rid = $rid;
            } else {
                // Update task
                $theTask = Task::find()
                    ->where(['id'=>$task_id])
                    ->one();
                if (!$theTask) {
                    throw new HttpException(404, 'Task not found.');
                }
                if (!in_array(USER_ID, [1, $theTask['cb'], $theTask['ub']])) {
                    throw new HttpException(403, 'Access denied.');
                }
            }

            $date = date('j/n', strtotime($_POST['date']));
            $time = substr($_POST['time'], 0, 5);
            $theTask->due_dt = $_POST['date'].' '.$_POST['time'];

            if ($_POST['time_fuzzy'] == 'm') {
                $time = 'morning';
                $theTask->due_dt = $_POST['date'].' 11:59:59';
            } elseif ($_POST['time_fuzzy'] == 'a') {
                $time = 'afternoon';
                $theTask->due_dt = $_POST['date'].' 17:59:59';
            } elseif ($_POST['time_fuzzy'] == 'e') {
                $time = '';
                $theTask->due_dt = $_POST['date'].' 23:59:59';
            }

            $theTask->description = $_POST['description'];
            $theTask->mins = $_POST['mins'];
            $theTask->fuzzy = 'time';
            if ($_POST['time_fuzzy'] == 't') {
                $theTask->fuzzy = 'none';
            }
            $theTask->is_priority = $_POST['is_priority'];
            $theTask->status = 'on';
            $theTask->save(false);

            $assigneesHtml = [];
            $theAssignees = User::find()
                ->select(['id', 'email', 'name'=>'nickname'])
                ->where(['id'=>$_POST['who']])
                ->asArray()
                ->all();
            foreach ($theAssignees as $user) {
                $assigneesHtml[] = '<span class="task-assignee text-muted">'.$user['name'].'</span>';
            }

            // Delete old assignees
            if ($task_id != 0) {
                Yii::$app->db->createCommand('DELETE FROM at_task_user WHERE task_id=:task_id', [':task_id'=>$theTask['id']])->execute();
            }

            // Re-assign
            foreach ($theAssignees as $user) {
                // Assign new assignees
                Yii::$app->db->createCommand('INSERT INTO at_task_user (task_id, user_id, assigned_dt) VALUES (:task_id, :user_id, :now)', [
                    ':task_id'=>$theTask['id'],
                    ':user_id'=>$user['id'],
                    ':now'=>NOW,
                ])->execute();
                // Email them all
                if ($user['id'] != USER_ID) {
                    $this->mgIt(
                        ($theTask['is_priority'] == 'yes' ? '#task #priority' : '#task').($task_id == 0 ? '' : ' #updated').' '.$theTask['description'].' | '.$date.' '.$time,
                        '//mg/task_assign',
                        [
                            'theTask'=>$theTask,
                        ],
                        [
                            ['from', 'notifcations@amicatravel.com', Yii::$app->user->identity->nickname, 'on IMS'],
                            ['to', $user['email'], $user['name']],
                            // ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        ]
                    );
                }
            }

            $today = strtotime(date('Y-m-d'));
            $taskDue = strtotime(substr($theTask['due_dt'], 0, 10));
            $taskToday = $taskDue == $today ? '<span class="task-today">Today</span>' : '';
            $taskOverdue = $taskDue < $today ? 'task-overdue' : '';
            $response = [
                'task_id'=>$theTask->id,
                'task_date'=>$date,
                'task_time'=>$time,
                'task_today'=>$taskToday,
                'task_overdue'=>$taskOverdue,
                'time_fuzzy'=>$_POST['time_fuzzy'],
                'description'=>$_POST['description'],
                'is_priority'=>$_POST['is_priority'],
                'assignees'=>implode(', ', $assigneesHtml),
            ];
            return $response;
        }

        $action = isset($_POST['action']) ? $_POST['action'] : '';
        $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : 0;

        if ($action == 'check') {
            $theTask = Task::find()->where(['id'=>$task_id])->one();
            if (!$theTask) {
                $return = [
                    'status'=>'NOK',
                    'message'=>'Task not found: #'.$task_id,
                ];
                die(json_encode($return));
            }

            $sql = 'SELECT u.name AS user_name, tu.* FROM persons u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id=:id ORDER BY lname';
            $theTaskUsers = Yii::$app->db->createCommand($sql, [':id'=>$theTask['id']])->queryAll();
    
            $forMe = false; // Task was assigned to me
            $taskCheckedByOne = false;
            $taskCheckedByAll = true;
            $iconClass = 'icon-check-empty';

            foreach ($theTaskUsers as $tu) {
                if ($tu['user_id'] == MY_ID) {
                    $forMe = true;
                    // Revert check
                    $tu['completed_dt'] = $tu['completed_dt'] == ZERODT ? NOW : ZERODT;
                    $sql= 'UPDATE at_task_user SET completed_dt=:completed_dt WHERE user_id=:user_id AND task_id=:task_id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [
                        ':completed_dt'=>$tu['completed_dt'],
                        ':user_id'=>MY_ID,
                        ':task_id'=>$theTask['id'],
                    ])->execute();
                }
                if ($tu['completed_dt'] == ZERODT) {
                    $taskCheckedByAll = false;
                } else {
                    $taskCheckedByOne = true;
                }
            }
    
            if ($forMe) {
                if ($taskCheckedByAll || ($taskCheckedByOne && $theTask['is_all'] == 'no')) {
                    $sql = 'UPDATE at_tasks SET status="off" WHERE id=:id LIMIT 1';
                    $iconClass = 'icon-check';
                } else {
                    $sql = 'UPDATE at_tasks SET status="on" WHERE id=:id LIMIT 1';
                }
                Yii::$app->db->createCommand($sql, [
                    ':id'=>$theTask['id'],
                ])->execute();

                $return = [
                    'status'=>'OK',
                    'icon'=>$iconClass,
                ];
                die(json_encode($return));
            }

            $return = [
                'status'=>'NOK',
                'icon'=>$iconClass,
                'message'=>'Click nhầm cmnr =!',
            ];
            die(json_encode($return));
        }

        $return = [
            'status'=>'NOK',
            'message'=>'Invalid action',            
        ];
        die(json_encode($return));
    }

    public function actionIndex($redir = '', $action = '', $taskId = 0)
    {
        return $this->render('task_index', [
            'redir'=>$redir,
            'taskId'=>$taskId,
            'action'=>$action,
        ]);
    }

    public function actionAssigned()
    {
        /*

$getAction = fRequest::getValid('action', array('', 'do', 'undo', 'delete'));
$getTaskId = fRequest::get('taskId', 'integer', 0);
$getRedir = fRequest::getValid('redir', array('', 'ref'));

if ($getTaskId != 0 && $getAction == 'delete') {
    $q = $db->query('SELECT description FROM at_tasks WHERE id=%i AND ub=%i LIMIT 1', $getTaskId, myID);
    if ($q->countReturnedRows() > 0) {
        $db->query('DELETE FROM at_tasks WHERE id=%i LIMIT 1', $getTaskId);
        $db->query('DELETE FROM at_task_user WHERE task_id=%i', $getTaskId);
    } else {
        show_error(403);
    }
}

if ($getTaskId != 0 && $getAction != '' && $getRedir == 'ref' && isset($_SERVER['HTTP_REFERER'])) {
    redirect($_SERVER['HTTP_REFERER']);
    exit;
}

$getCat = seg2;
if ($getCat == '') {
  $metaT = 'Nhiệm vụ tôi phải làm';
  $q = $db->query('SELECT t.*, (SELECT name FROM persons u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="on" AND tu.user_id=%i ORDER BY due_dt, is_priority LIMIT 1000', myID);
} elseif ($getCat == 'assigned') {
  $metaT = 'Nhiệm vụ tôi giao cho người khác';
  $q = $db->query('SELECT *, "" AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND ub=%i AND tu.user_id!=%i ORDER BY status, due_dt, is_priority LIMIT 1000', myID, myID);
} elseif ($getCat == 'done') {
  $metaT = 'Nhiệm vụ tôi đã làm xong';
  $q = $db->query('SELECT t.*, (SELECT name FROM persons u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="off" AND tu.user_id=%i ORDER BY due_dt DESC, is_priority LIMIT 1000', myID);
} else {
    show_error(404);
}
$theTasks = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Task task id list
foreach ($theTasks as $t) $theTaskIdList[] = $t['id'];

// The task users
if (empty($theTaskIdList)) {
    $theTaskUsers = array();
} else {
    $q = $db->query('SELECT u.name AS user_name, tu.* FROM persons u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname');
    
    $theTaskUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}


$pageM = 'tasks';
$pageB = array(
    anchor('tasks', __('mn', 'Tasks')),
    );

$thisYear = date('Y');
$today = date('Y-m-d');
        */
        $sql = 'SELECT *, "" AS ub_name,
            IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
            IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
            FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND ub=:me AND tu.user_id!=:me ORDER BY status, due_dt, is_priority LIMIT 1000';
        $theTasks = Task::findBySql($sql, [':me'=>MY_ID])
            ->asArray()
            ->all();

        // Task task id list
        $taskIdList = [];
        $taskUserList = [];
        foreach ($theTasks as $task) {
            $taskIdList[] = $task['id'];
        }
        if (!empty($taskIdList)) {
            $sql = 'SELECT u.name, u.id, tu.task_id, tu.completed_dt, tu.assigned_dt FROM persons u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $taskIdList).') ORDER BY u.lname';
            $taskUserList = Yii::$app->db->createCommand($sql)->queryAll();
        }

        return $this->render('task_assigned', [
            'theTasks'=>$theTasks,
            'taskIdList'=>$taskIdList,
            'taskUserList'=>$taskUserList,
        ]);
    }

    public function actionDone()
    {
        $theTasks = Task::findBySql('SELECT t.*, u.name FROM at_tasks t, persons u, at_task_user tu WHERE tu.task_id=t.id AND tu.user_id=u.id AND u.id=:me AND tu.completed_dt!=0 ORDER BY tu.completed_dt DESC LIMIT 100', [':me'=>Yii::$app->user->id])
            ->asArray()
            ->all();
        return $this->render('task_done', [
            'theTasks'=>$theTasks,
        ]);
    }

    public function actionCc($redir = '')
    {
        $theTask = new Task;

        $theTask->scenario = 'task/c';

        if ($theTask->load(Yii::$app->request->post()) && $theTask->validate()) {
            $theTask->save(false);
            return $this->redirect(DIR.'/'.$redir);
        }

        return $this->render('task_u', [
            'theTask'=>$theTask,
        ]);
    }

    public function actionR($id = 0)
    {
        $theTask = Task::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTask) {
            throw new HttpException(404, 'Task not found');
        }

        return $this->render('tasks_r', [
            'theTask'=>$theTask,
        ]);
    }

    public function actionUu($id = 0) {
        $theTask = Task::find()
            ->where(['id'=>$id])
            ->one();

        if (!$theTask) {
            throw new HttpException(404, 'Task not found');
        }

        $theTask->scenario = 'task/u';

        if ($theTask->load(Yii::$app->request->post()) && $theTask->validate()) {
            $theTask->save();
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('tasks_u', [
            'theTask'=>$theTask,
        ]);
    }

    public function actionD($id = 0)
    {
        $theTask = Task::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theTask) {
            throw new HttpException(404, 'Task not found');
        }

        return $this->render('tasks_d', [
            'theTask'=>$theTask,
        ]);
    }

}
