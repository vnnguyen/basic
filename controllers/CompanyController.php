<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

use common\models\Company;
use common\models\Venue;
use common\models\Dv;
use common\models\Dvt;
use common\models\Dvg;
use common\models\Note;
use common\models\User;
use common\models\Search;
use common\models\Meta;
use common\models\File;
use common\models\Message;


class CompanyController extends MyController
{
	public function actionIndex($name = '') {
		$query = Company::find();

		if (strlen($name) > 2) {
			$query->andWhere(['like', 'name', $name]);
		}

		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theCompanies = $query
			->select(['id', 'name', 'status', 'info'])
			->with(['metas', 'venues'])
			->orderBy('name')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();
		return $this->render('companies', [
			'pagination'=>$pagination,
			'theCompanies'=>$theCompanies,
			'name'=>$name,
		]);
	}

	public function actionC($id = 0) {
		$theCompany = new Company;
		$theCompany->scenario = 'company/c';

		if ($theCompany->load(Yii::$app->request->post()) && $theCompany->validate()) {

			$theCompany->created_at = NOW;
			$theCompany->created_by = MY_ID;
			$theCompany->updated_at = NOW;
			$theCompany->updated_by = MY_ID;
			$theCompany->search = ' '.str_replace('-', '', \fURL::makeFriendly($theCompany->name, '-'));

			$theCompany->save(false);
			//return $this->redirect('@web/companies/r/'.$theCompany->id);
			return $this->redirect('@web/companies');
		}
				
		return $this->render('companies_c', [
			'theCompany'=>$theCompany,
		]);
	}

	public function actionR($id = 0) {
		$theCompany = Company::find()
			->where(['id'=>$id])
			->with([
				'metas'=>function($query) {
					$query->andWhere('rtype = "company"');
					},
				])
			->asArray()
			->one();
		if (!$theCompany) {
			throw new HttpException(404, 'Company not found');
		}

		$thePeople = User::find()
			->select(['id', 'name', 'fname', 'lname', 'email'])
			->where(['status'=>'on', 'is_member'=>'yes'])
			->orderBy('lname, fname')
			->asArray()
			->all();

		if (isset($_POST['body'])) {
			$utag = false;
			$itag = false;
			$title = isset($_POST['title']) ? trim($_POST['title']): '';
			$body = $_POST['body'];

			if (strpos($title, '#urgent') !== false) {
				$utag = true;
				$title = str_replace('#urgent', '', $title);
			}
			if (strpos($title, '#important') !== false) {
				$itag = true;
				$title = str_replace('#important', '', $title);
			}

			$title = trim($title);
			$title = trim($title, '#');


			// Name mentions
			$toList = [];
			$toEmailList = [];
			$toIdList = [];
			if (isset($_POST['to']) && $_POST['to'] != '') {
				foreach ($thePeople as $person) {
					$mention = '@['.$person['name'].']';
					if (strpos($_POST['to'], $mention) !== false) {
						$toList[$person['id']] = $person;
						$toEmailList[] = $person['email'];
						$toIdList[] = $person['id'];
					}
				}
			}
			$toEmailList = array_unique($toEmailList);

			// Save note

			define('ICT', date('Y-m-d H:i:s', strtotime('+7 hours')));

			$theNote = new Note;
			$theNote->scenario = 'notes_c';

			$theNote->co = ICT;
			$theNote->cb = MY_ID;
			$theNote->uo = ICT;
			$theNote->ub = MY_ID;
			$theNote->status = 'on';
			$theNote->via = isset($noteViaEmail) && $noteViaEmail ? 'email' : 'web';
			$theNote->priority = 'A1';
			if ($itag) {
				$theNote->priority = 'C1';
			}
			if ($utag) {
				$theNote->priority = 'A3';
			}
			$theNote->from_id = isset($noteFromId) && isset($noteToId) ? $noteFromId : MY_ID;
			$theNote->m_to = isset($noteFromId) && isset($noteToId) ? $noteToId : 0;
			$theNote->title = $title;
			$theNote->body = $_POST['body'];
			$theNote->rtype = 'company';
			$theNote->rid = $theCompany['id'];

			if (!$theNote->save(false)) {
				die('NOTE NOT SAVED');
			}

			if (!empty($toIdList)) {
				$nTo = [];
				foreach ($toIdList as $to) {
					$nTo[] = [$theNote->id, $to];
				}
				Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
			}

			$relUrl = 'https://my.amicatravel.com/companies/r/'.$theCompany['id'];
			$relName = $theCompany['name'];

			// Upload files
			$fileList = '';
			if (isset($_POST['fileid']) && isset($_POST['filename']) && is_array($_POST['fileid']) && is_array($_POST['filename']) &&  count($_POST['fileid']) == count($_POST['filename'])) {
				foreach ($_POST['fileid'] as $i => $fileId) {
					$newFileName = $_POST['filename'][$i];
					$rawFileExt = strrchr($newFileName, '.');
					$rawFileName = $fileId.$rawFileExt;
					$rawFilePath = '/var/www/my.amicatravel.com/www/assets/plupload_2.1.9/'.$rawFileName;
					if (file_exists($rawFilePath)) {
						$fileUid = Yii::$app->security->generateRandomString(10);
						$fileSize = filesize($rawFilePath);
						$imgSize = @getimagesize($rawFilePath);
						if ($imgSize) {
							$fileImgSize = $imgSize[0].'×'.$imgSize[1];
						} else {
							$fileImgSize = '';
						}
						Yii::$app->db->createCommand()
							->insert('at_files', [
								'co'=>ICT,
								'cb'=>MY_ID,
								'uo'=>ICT,
								'ub'=>MY_ID,
								'name'=>$newFileName,
								'ext'=>$rawFileExt,
								'size'=>$fileSize,
								'img_size'=>$fileImgSize,
								'uid'=>$fileUid,
								'filegroup_id'=>1,
								'rtype'=>'company',
								'rid'=>$theCompany['id'],
								'n_id'=>$theNote['id'],
							])
							->execute();
						$newFileId = Yii::$app->db->getLastInsertID();
						// New dir
						$newDir = '/var/www/my.amicatravel.com/www/upload/user-files/'.substr(ICT, 0, 7).'/';
						@mkdir($newDir);

						// New name
						$newName = 'file-'.MY_ID.'-'.$newFileId.'-'.$fileUid;

						// Move upload file to new (official) location
						if (copy($rawFilePath, $newDir.$newName)) {
							unlink($rawFilePath);
							$fileList .= '<br>+ <a href="https://my.amicatravel.com/files/r/'.$newFileId.'">'.$newFileName.'</a>';
							//echo '<br><a href="/files/r/', $newFileId, '">', $newName, ' = ', $newFileName, '</a>';
						} else {
						Yii::$app->db->createCommand()
							->delete('at_files', [
								'id'=>$newFileId,
							])
							->execute();
						}
					}
				}
			}

			if ($fileList != '') {
				$body = $fileList.'<br>'.$body;
			}

			// Send email

			if (!empty($toEmailList)) {
				$subject = $title;
				if ($itag) {
					$subject = '#important '.$subject;
				}
				if ($utag) {
					$subject = '#urgent '.$subject;
				}
				if ($subject == '') {
					$subject = 'No title';
				}
				$subject .= ' | Company: '.$relName;

				$args = [
					['from', 'notifications@amicatravel.com', Yii::$app->user->identity->name, 'Amica Travel'],
					['reply-to', Yii::$app->user->identity->email],
					['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
				];
				foreach ($toList as $id=>$user) {
					$args[] = ['to', $user['email'], $user['lname'], $user['fname']];
				}
				$this->mgIt(
					'ims | '.$subject,
					'//mg/note_added',
					[
						'toList'=>$toList,
						'theNote'=>$theNote,
						'relUrl'=>$relUrl,
						'body'=>$body,
					],
					$args
				);
			}
			return $this->redirect('@web/companies/r/'.$theCompany['id']);
		}

		$companyFiles = File::find()
			->where(['rtype'=>'company', 'rid'=>$id])
			->asArray()
			->all();

		$theNotes = Note::find()
			->where(['rtype'=>'company', 'rid'=>$id])
			->with([
				'from'=>function($q) {
					return $q->select(['id', 'name']);
				},
				'to'=>function($q) {
					return $q->select(['id', 'name']);
				},
			])
			->orderBy('co DESC')
			->all();
				
		return $this->render('companies_r', [
			'theCompany'=>$theCompany,
			'companyFiles'=>$companyFiles,
			'theNotes'=>$theNotes,
			'thePeople'=>$thePeople,
		]);
	}

	public function actionU($id = 0) {
		$theCompany = Company::find()
			->where(['id'=>$id])
			->with(['metas', 'updatedBy'])
			->one();

		if (!$theCompany) {
			throw new HttpException(404, 'Company not found');
		}

		$theCompany->scenario = 'company/u';

		$uploadDir = 'companies/'.substr($theCompany['created_at'], 0, 7).'/'.$theCompany['id'];
		\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').'/upload/'.$uploadDir);

        $ckfSessionName = 'company'.$theCompany['id'];
        $ckfSessionValue = [
            'ckfResourceName'=>'upload',
            'ckfResourceDirectory'=>$uploadDir,
        ];
        Yii::$app->session->set('ckfAuthorized', true);
        Yii::$app->session->set('ckfRole', 'user');
        Yii::$app->session->set($ckfSessionName, $ckfSessionValue);

		if ($theCompany->load(Yii::$app->request->post()) && $theCompany->validate()) {
			if ($theCompany->save(false)) {
				Yii::$app->session->setFlash('success', 'Company has been updated: '.$theCompany['name']);
				return $this->redirect('@web/companies/r/'.$theCompany['id']);
			}
		}
				
		return $this->render('companies_u', [
			'theCompany'=>$theCompany,
		]);
	}

	// Cac dv cua cong ty nay
	public function actionDv($id = 0)
	{
		$theCompany = Company::find()
			->where(['id'=>$id])
			->one();

		if (!$theCompany) {
			throw new HttpException(404, 'Company not found');
		}

		$theVenues = Venue::find()
			->where(['company_id'=>$id])
			->orderBy('name')
			->all();

		$theDvx = Dv::find()
			->where(['company_id'=>$id])
			->orderBy('grouping, name')
			->all();

		return $this->render('companies_dv', [
			'theCompany'=>$theCompany,
			'theDvx'=>$theDvx,
			'theVenues'=>$theVenues,
		]);
	}
}
