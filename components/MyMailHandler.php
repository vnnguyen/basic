<?php

namespace app\components;

use Yii;
// use yiicod\mailqueue\models\MailRepositoryInterface;
use yiicod\mailqueue\components\MailHandler;


class MyMailHandler extends MailHandler
{
    // const STATUS_SENT = 1;

    // const STATUS_PENDING = 0;

    // const STATUS_FAILED = 2;


    public $conditions = [];
    /**
     * Find mails
     *
     * @return mixed
     */
    public function findAll()
    {
        $class = Yii::$app->get('mailqueue')->modelMap['mailQueue']['class'];
        $query = $class::find()
            ->where(sprintf('%s=:pending', $class::attributesMap()['fieldStatus']));
        if(isset($conditions['where'])) {
            $query->andWhere($this->conditions['where']);
        }
        $models = $query->params([':pending' => self::STATUS_PENDING])
            ->orderBy($class::attributesMap()['fieldPriority'])
            ->limit(isset($conditions['limit']) ? $conditions['limit']: 60)
            ->all();


        return $models;
    }

}
