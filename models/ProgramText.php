<?php

namespace app\models;

class ProgramText extends MyActiveRecord
{
    public static function tableName()
    {
        return 'program_text';
    }

    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id'=>'program_id']);
    }
}
