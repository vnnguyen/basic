<?php
namespace app\models;

use Yii;

/**
 * This is the class for all searchable entities, eg posts, messages, files, etc
 */

class Dot extends MyActiveRecord
{

    public static function tableName()
    {
        return 'dots';
    }
}
