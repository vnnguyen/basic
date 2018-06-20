<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\InboxMail;
use common\models\User;
use common\models\Kase;
use yii\web\HttpException;

class InboxmailController extends MyController
{
	public function actionIndex()
	{
		$query = InboxMail::find()
			->where(['status'=>'on']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);
		$theMails = $query
			//->select(['id', 'dt', 'from', 'sender', 'recipient', 'subject', 'attachments', 'status'])
			->orderBy('created_at DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->all();
		return $this->render('inboxmails', [
			'pages'=>$pages,
			'theMails'=>$theMails,
			]
		);
	}

	public function actionR($id = 0)
	{
		$theMail = InboxMail::find()
			->where(['id'=>$id])
			->one();
		if (!$theMail) {
			throw new HttpException(404, 'Mail not found');
		}

		$customerEmails = [];
		$emailAddressesText = $theMail['from'];
		if (strpos($emailAddressesText, 'amicatravel') !== false || strpos($emailAddressesText, 'amica-travel') !== false) {
			$emailAddressesText = $theMail['to'];
		}
		$emailAddressesParts = explode(' ', $emailAddressesText);
		foreach ($emailAddressesParts as $part) {
			if (strpos($part, '@') !== false) {
				$customerEmails[] = str_replace(['<', '>', ','], ['', '', ''], strtolower($part));
			}
		}

		$theSenders = Yii::$app->db
			->createCommand('SELECT u.id, u.fname, u.lname, u.gender FROM at_meta m, persons u WHERE m.k="email" AND m.rtype="user" AND u.id=m.rid AND m.v IN ("'.implode('","', $customerEmails).'")')
			->queryAll();

		$senderIds = [];
		$theCases = [];
		if (!empty($theSenders)) {
			foreach ($theSenders as $sender) {
				$senderIds[] = $sender['id'];
			}
			$theCases = Yii::$app->db
				->createCommand('SELECT k.id, k.name, k.status, k.deal_status FROM at_cases k, at_case_user cu WHERE cu.case_id=k.id AND cu.user_id IN ('.implode(',', $senderIds).') ORDER BY status, k.id DESC')
				->queryAll();
		}

		return $this->render('inboxmails_r', [
			'theMail'=>$theMail,
			'theSenders'=>$theSenders,
			'theCases'=>$theCases,
		]);
	}

	// Download file
	public function actionF($id = 0, $name = '')
	{
		$theMail = InboxMail::find()
			->where(['id'=>$id])
			->one();
		if (!$theMail)
			throw new HttpException(404, 'Mail not found');

		$filePath = Yii::getAlias('@webroot').'/storage/mailgun/'.substr($theMail->dt, 0, 7).'/'.$theMail->id.'/'.$theMail->id.'-'.sha1(urldecode($name));
		if (file_exists($filePath)) {
			$files = unserialize($theMail->files);
			foreach ($files as $li) {
				if ($li['name'] == urldecode($name)) {
					return Yii::$app->response->sendFile($filePath, $li['name']);
				}
			}
		}
	}

	public function actionBh($id = 0)
	{
		$theMail = InboxMail::find()
			->select(['body_full'])
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theMail) {
			die('Mail not found');
		}
		$theMail['body_full'] = strip_tags($theMail['body_full'], '<a><b><br><hr><img><p><div><table><tbody><thead><tr><td><em><span><strong><ul><ol><li>');
		$theMail['body_full'] = str_replace([' style='], [' x='], $theMail['body_full']);
		echo '<!DOCTYPE html><html><head><meta charset="utf-8"><link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"></head><body>'.$theMail['body_full'].'</body></html>';
	}

	// Move inquiry to another case
	public function actionU($id = 0)
	{
		throw new HttpException(403, 'Email cannot be edited.');
	}

	public function actionD($id = 0)
	{
		$theMail = InboxMail::find()
			->select(['status', 'subject'])
			->where(['id'=>$id])
			->one();
		if (!$theMail) {
			throw new HttpException(404, 'Mail not found');
		}

		if ($theMail->status != 'deleted') {
			// $theMail->scenario = 'delete';
			$theMail->status = 'deleted';
			if ($theMail->save(false)) {
				Yii::$app->session->setFlash('success', 'Mail has been marked DELETED: '.$theMail->subject);
			}
		} else {
			if ($theMail->delete()) {
				Yii::$app->session->setFlash('success', 'Mail has been DELETED: '.$theMail->subject);
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}
}
