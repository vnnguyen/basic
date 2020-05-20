<?

Yii::setAlias('common', 'D:/wamp/www/basic/common-ims/');

return [
	'components.cache' => [
		'class' => 'yii\caching\FileCache',
	],

	'components.mail' => [
		'class' => 'yii\swiftmailer\Mailer',
	],

	'components.db' => [
		'class' => 'yii\db\Connection',
		'dsn' => 'mysql:host=localhost;dbname=my_amica',
		'username' => 'root',
		'password' => '',
		'charset' => 'utf8',
		//'enableSchemaCache'=>true,
		//'schemaCacheDuration'=>0,
		//'schemaCacheExclude'=>['at_users'],
		//'enableQueryCache'=>true,
		//'queryCacheDuration'=>3600,
		// 'tablePrefix'=>'at_',
	],

	'components.user' => [
		'identityClass' => 'app\models\User',
		'enableAutoLogin'=>true,
		'authTimeout'=>720000,
		'loginUrl'=>['login/index'],
	],

];