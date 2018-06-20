<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Group;
use common\models\User;
use common\models\Permission;

class GroupController extends MyController
{
    public function actionIndex($type = '')
    {
        $query = Group::find()
            ->where(['account_id'=>ACCOUNT_ID]);
        if ($type != ''){
            $query->andWhere(['stype'=>$type]);
        }
        $theGroups = $query
            ->orderBy('stype, name')
            ->limit(1000)
            ->asArray()
            ->all();

        return $this->render('group_index', [
            'theGroups'=>$theGroups,
            'type'=>$type,
        ]);
    }

    public function actionC()
    {
        $theGroup = new Day;

        $caseOwnerList = User::find()
            ->select('id, name, email')
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if ($theGroup->load($_POST) && $theGroup->validate()) {
            $theGroup->save();
            return Yii::$app->response->redirect('cases/r/'.$id);
        }
        return $this->render('days_u', [
            'theGroup'=>$theGroup,
            ]);
    }

    public function actionR($id = 0) {
        $theGroup = Group::find()
            ->with([
                'members'=>function($q) {
                    return $q->select('*')->orderBy('lname, fname');
                }
                ])
            ->where(['id'=>$id])
            ->asArray()
            ->one();

        if (!$theGroup) {
            throw new HttpException(404, 'Group not found');
        }

        return $this->render('group_r', [
            'theGroup'=>$theGroup,
        ]);
    }

    public function actionU($id = 0)
    {
        $theGroup = Group::findOne($id);

        if (!$theGroup)
            throw new HttpException(404, 'Group not found.');

        if ($theGroup->load(Yii::$app->request->post()) && $theGroup->validate()) {
            $theGroup->save();
            return $this->redirect('/groups/r/'.$id);
        }

        return $this->render('group_u', [
            'theGroup'=>$theGroup,
        ]);
    }

}
