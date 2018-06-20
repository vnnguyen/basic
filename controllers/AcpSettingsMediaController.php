<?php

namespace app\controllers;

use Yii;
use common\models\Kase;
use common\models\User;
use common\models\Product;
use common\models\ProfileMember;

class AcpSettingsMediaController extends MyController
{

    public function actionIndex()
    {
        return $this->render('acp-settings-media_index', [
        ]);
    }

    // Amica members only
    public function actionAtUsers()
    {
        $theUsers = User::find()
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orderBy('lname, fname')
            ->with(['metas', 'profileMember'])
            ->all();
        return $this->render('persons', [
            'theUsers'=>$theUsers,
        ]);
    }

    // View a user
    public function actionAtUsersR($id = 0)
    {
        $theUser = User::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($query) {
                    $query->andWhere(['rtype'=>'user']);
                },
                'country',
                'roles',
                'cases',
                'tours',
                'refCases'
            ])
            ->asArray()
            ->one();

        if (!$theUser)
            throw new HttpException(404, 'User not found');

        $userMemberProfile = ProfileMember::find()
            ->where(['user_id'=>$id])
            ->asArray()
            ->one();

        return $this->render('persons_r', [
            'theUser'=>$theUser,
            'userMemberProfile'=>$userMemberProfile,
        ]);
    }
}
