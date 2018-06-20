<?php
namespace app\helpers;

use Yii;

class User
{
	// Yii::$app->user->inGroups('quanly,banhang')
	// Yii::$app->user->inGroups('any:quanly,banhang')
	public static function inGroups($groupNames)
	{
		if (!isset(\Yii::$app->user->identity) || !\Yii::$app->user->identity) {
			return false;
		}

		$groups = [
			'member'=>[1],
			'lanhdao'=>[2,3,4],
			'quanly'=>[1,2,3,4,8,11,118,695,4432,4065,4828,7756,2382,11724,36654,22447,24821,26435,],
			'it'=>[1,15931,24404,],
			'marketing'=>[695,9127,14671,16079,18598],
			'banhang'=>[2,3,4,6,13,772,1033,1087,1502,1677,2382,3066,4432,4829,5046,5372,10068,11723,11724,36654,12665,12952,14654,15860,15861,17090,17097,17089,17436,18519,23669,24821,26435,28228,28319,],
			'banhang-fr'=>[23669],
			'banhang-en'=>[772,15860],
			'guide'=>[],
			'guide-fr'=>[],
			'siemreap'=>[3404],
			'cskh'=>[1351,7756,9881,21495,23352],
			'dvdv'=>[8,9198],
			'dieuhanh'=>[7,118,4125,5270,8162,15081,24820],
			'ketoan'=>[11,16,17,4065,20787],
			'thuctap'=>[21495],
			'nhansu'=>[22447,24229,],
		];

		$any = false;
		if (substr($groupNames, 0, 4) == 'any:') {
			$groupNames = substr($groupNames, 4);
			$any = true;
		}

		$names = explode(',', $groupNames);
		foreach ($names as $name) {
			if (!isset($groups[trim($name)])) {
				return false;
			}
		}

		if ($any) {
			foreach ($names as $name) {
				if (in_array(\Yii::$app->user->identity->id, $groups[trim($name)])) {
					return true;
				}
			}
			return false;
		} else {
			foreach ($names as $name) {
				if (!in_array(\Yii::$app->user->identity->id, $groups[trim($name)])) {
					return false;
				}
			}
			return true;
		}
	}
}