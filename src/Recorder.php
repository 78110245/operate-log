<?php

namespace xlerr\operatelog;

use xlerr\operatelog\models\OperateLog;
use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;
use yii\web\Application;

class Recorder
{
    public static $excepts = [
        OperateLog::class,
    ];

    public static function register($excepts = [])
    {
        if (Yii::$app instanceof Application) {
            Yii::debug('operate-log registered', __METHOD__);
            self::$excepts = array_merge(self::$excepts, $excepts);
            Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_INSERT, [__CLASS__, 'record']);
            Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_UPDATE, [__CLASS__, 'record']);
            Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_DELETE, [__CLASS__, 'record']);
        }
    }

    public static function record(Event $event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        foreach (self::$excepts as $except) {
            if ($sender instanceof $except) {
                return;
            }
        }

        Yii::debug($event->name, __CLASS__);

        /** @var \yii\web\Request $request */
        $request = Yii::$app->request;

        $newData = $oldData = null;
        switch ($event->name) {
            case ActiveRecord::EVENT_AFTER_INSERT:
                $newData = Json::encode($sender->attributes);
                $type    = OperateLog::TYPE_INSERT;
                break;
            case ActiveRecord::EVENT_AFTER_UPDATE :
                if (empty($event->changedAttributes)) {
                    return;
                }
                $oldData = Json::encode($event->changedAttributes);
                $newData = Json::encode(array_intersect_key($sender->attributes, $event->changedAttributes));
                $type    = OperateLog::TYPE_UPDATE;
                break;
            case ActiveRecord::EVENT_AFTER_DELETE :
                $type = OperateLog::TYPE_DELETE;
                break;
        }

        $tableName = $sender->tableSchema->name;

        $model = new OperateLog();
        $model->setAttributes([
            'request_url'    => $request->url,
            'request_method' => $request->method,
            'request_params' => Json::encode([
                'get'  => $request->get(),
                'post' => $request->post(),
            ]),
            'table'          => $tableName,
            'server_ip'      => $_SERVER['SERVER_ADDR'] ?? '',
            'client_ip'      => $request->getUserIP(),
            'user_id'        => Yii::$app->user->getId(),
            'operated_at'    => date('Y-m-d H:i:s'),
            'type'           => $type,
            'old_attr'       => $oldData,
            'new_attr'       => $newData,
        ]);
        if (!$model->insert()) {
            Yii::debug($model->errors, __METHOD__);
        }
    }
}
