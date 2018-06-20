<?
namespace common\models;

use common\models\User;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

class LoginResetForm extends Model
{
	public $password;
	public $password2;

	private $user;
	private $reset;

	public function __construct($token, $config = [])
	{
		$sql = 'SELECT * FROM at_login_resets WHERE token=:token LIMIT 1';
		$this->reset = Yii::$app->db->createCommand($sql, [':token'=>$token])->queryOne();
		if (!$this->reset) {
			Yii::$app->session->setFlash('danger', 'Invalid token.');
			return Yii::$app->response->redirect('@web/login');
		}
		if (strtotime($this->reset['expiry_dt']) < strtotime(NOW)) {
			Yii::$app->session->setFlash('danger', 'Token has expired');
			return Yii::$app->response->redirect('@web/login');
		}
		$this->user = User::find()
			->where(['id'=>$this->reset['user_id'], 'status'=>'on', 'is_member'=>'yes'])
			->one();
		if (!$this->user) {
			Yii::$app->session->setFlash('danger', 'Invalid token.');
			return Yii::$app->response->redirect('@web/login');
		}

		parent::__construct($config);
	}

	public function attributeLabels()
	{
		return [
			'password'=>'Your new password',
			'password2'=>'Repeat your new password',
		];
	}

	public function rules()
	{
		return [
			[['password', 'password2'], 'required', 'message'=>'This field is required.'],
			[['password'], 'string', 'min' => 6],
			[['password2'], 'compare', 'compareAttribute' => 'password', 'message'=>'Passwords do not match.'],
		];
	}

	public function resetPassword()
	{
		$user = $this->user;
		$user->password = Yii::$app->security->generatePasswordHash($this->password);
		Yii::$app->db->createCommand()
			->delete('at_login_resets', ['id'=>$this->reset['id']])
			->execute();
		return $user->save(false);
	}

	public function sendEmail()
	{
		\Yii::$app->mail->htmlLayout = false;
		\Yii::$app->mail->compose(['html'=>'@app/mail/login_reset'], ['password' => $this->password])
			->setFrom(['noreply-ims@amicatravel.com' => \Yii::$app->name])
			->setTo([$this->user->email => $this->user->name])
			->setBcc(['hn.huan@gmail.com' => 'Huân H.'])
			->setSubject('New password for ' . \Yii::$app->name)
			->send();
	}
}