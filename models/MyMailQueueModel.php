<?php

namespace app\models;

use Yii;
use \yiicod\mailqueue\models\MailQueueModel;

/**
 * This is the model class for table "mailqueue".
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $attachs
 * @property integer $priority
 * @property integer $status
 * @property string $createDate
 * @property string $updateDate
 */
class MyMailQueueModel extends MailQueueModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailqueue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['from', 'to', 'subject', 'body', 'priority', 'status'], 'required'],
            // [['body', 'attachs'], 'string'],
            // [['priority', 'status'], 'integer'],
            // [['createDate', 'updateDate'], 'safe'],
            // [['from', 'to', 'subject'], 'string', 'max' => 100],
        ];
    }
    public static function attributesMap()
    {
        return [
            'fieldFrom' => 'from',
            'fieldTo' => 'to',
            'fieldMailer' => 'mailer',
            'fieldSubject' => 'subject',
            'fieldBody' => 'body',
            'fieldPriority' => 'priority',
            'fieldAttaches' => 'attaches',
            'fieldStatus' => 'status',
            'fieldCreatedDate' => 'createDate',
            'fieldUpdatedDate' => 'updateDate',
        ];
    }

    public function getData(): array
    {
        return [
            'to' => $this->to,
            'from' => $this->from,
            'subject' => $this->subject,
            'mailer' => $this->mailer,
            'body' => $this->body,
            'priority' => $this->priority,
            'status' => $this->status ?: 0, // @todo Think about this
            'attaches' => $this->getAttaches(),
            'createDate' => $this->createDate,
            'updateDate' => $this->updateDate,

        ];
    }
}
