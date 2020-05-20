<?php
namespace app\models;

use yii\base\Model;

class TourInBnForm extends Model
{
    public $template;
    public $language;
    public $logo;
    public $names;
    public $extra;
    public $note;
    public $output = 'pdf-download';

    public function attributeLabels()
    {
        return [
            'template'=>'Banner template',
            'names'=>'Pax names (max 3 lines)',
            'extra'=>'Extra information',
            'note'=>'Note (tour code, pax, location, time etc)',
        ];
    }

    public function rules()
    {
        return [
            [['template', 'language', 'logo', 'names', 'extra', 'note'], 'trim'],
            [['template', 'language', 'logo', 'names', 'output'], 'required', 'message'=>'Required'],
        ];
    }

}