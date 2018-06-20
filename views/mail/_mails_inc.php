<?

Yii::$app->params['page_icon'] = 'envelope-o';

Yii::$app->params['page_title'] = 'Email messages';

Yii::$app->params['page_breadcrumbs'] = [
	['Email', '@web/mails'],
];

Yii::$app->params['page_actions'] = [];

if (isset($theMail['id'])) {
	Yii::$app->params['page_breadcrumbs'][] = ['View', '@web/mails/r/'.$theMail['id']];
	if (SEG2 == 'r') {
		Yii::$app->params['page_title'] = $theMail['subject'] == '' ? '( No subject )' : $theMail['subject'];
	} elseif (SEG2 == 'u') {
		Yii::$app->params['page_title'] = 'Edit: '.$theMail['subject'];
		Yii::$app->params['page_breadcrumbs'][] = ['Edit', '@web/mails/u/'.$theMail['id']];
	} elseif (SEG2 == 'd') {
		Yii::$app->params['page_title'] = 'Delete: '.$theMail['subject'];
		Yii::$app->params['page_breadcrumbs'][] = ['Xoá', '@web/mails/d/'.$theMail['id']];
	}
	Yii::$app->params['page_actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'mails/r/'.$theMail['id'], 'active'=>SEG2=='r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'mails/u/'.$theMail['id'], 'active'=>SEG2=='u'],
	];
	Yii::$app->params['page_actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'mails/d/'.$theMail['id'], 'active'=>SEG2=='d', 'class'=>'btn-danger'],
	];
}