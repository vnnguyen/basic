<?
use yii\helpers\Html;
$db = new \fDatabase('mysql', 'amica_my', 'amica_my', '2w#E4r%T', 'localhost');
define('myID', Yii::$app->user->id);
define('myName', Yii::$app->user->identity->name);

if ( ! function_exists('anchor')) {
	function anchor($url, $text) {
		return Html::a($text, $url);
	}
}