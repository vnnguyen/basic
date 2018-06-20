<?
namespace app\controllers;

use common\models\BugForm;
use common\models\Post;
use Yii;

class HelpController extends MyController
{
	public function actionIndex()
	{
		Yii::$app->language = 'vi';
		return $this->render('help');
	}

	public function actionBug()
	{
		$model = new BugForm;

		if ($model->load($_POST) && $model->validate()) {
			Yii::$app->mail->compose()
				->setTo('hn.huan@gmail.com')
				->setFrom([Yii::$app->user->identity->email => Yii::$app->user->identity->name])
				->setSubject('Góp ý IMS')
				->setTextBody($model->uri.chr(10).$model->happened.chr(10).$model->expected.chr(10).$model->comment)
				->send();
			Yii::$app->session->setFlash('success', 'Thank you. Your report has been sent.');
			return $this->goHome();
		}

		return $this->render('help_bug', ['model'=>$model]);
	}

	public function actionAbout()
	{
		return $this->render('help_about');
	}

	public function actionDocs($page = 0)
	{
		if ($page == 0) {
			$theEntry = Post::find()
				->where(['channel'=>'help-doc', 'status'=>'on'])
				->orderBy('sorder')
				->asArray()
				->one();
		} else {
			$theEntry = Post::find()
				->where(['channel'=>'help-doc', 'status'=>'on', 'id'=>$page])
				->orderBy('sorder')
				->asArray()
				->one();
		}

		if (!$theEntry) {
			$theEntry['id'] = 0;
			$theEntry['title'] = 'Hướng dẫn sử dụng';
			$theEntry['body'] = '<div class="alert alert-danger">Nội dung đang được xây dựng</div>';
		}

		$theEntries = Post::find()
			->select(['id', 'title'])
			->where(['channel'=>'help-doc', 'status'=>'on'])
			->orderBy('sorder')
			->asArray()
			->all();
		return $this->render('help_docs', [
			'theEntries'=>$theEntries,
			'theEntry'=>$theEntry,
		]);
	}

	public function actionChangelog()
	{
		return $this->render('help_changelog');
	}

	public function actionRoadmap()
	{
		return $this->render('help_roadmap');
	}

	public function actionFaq()
	{
		return $this->render('help_faq');
	}

	public function actionNews()
	{
		return $this->render('help_news');
	}
}
