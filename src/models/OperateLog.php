<?php

namespace xlerr\operatelog\models;

/**
 * This is the model class for table "{{%operate_log}}".
 * @property int $id
 * @property string $request_url 请求地址
 * @property string $request_params 请求参数
 * @property string $request_method 请求方法
 * @property int $type 操作类型1-新增2-修改3-删除4-登录
 * @property string $table 表名
 * @property string $old_attr 旧数据
 * @property string $new_attr 新数据
 * @property string $server_ip 服务器ip
 * @property string $client_ip 客服端ip
 * @property int $user_id 用户编号
 * @property string $memo 备注
 * @property string $operated_at 访问时间
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class OperateLog extends \yii\db\ActiveRecord
{
    const TYPE_INSERT = 1;
    const TYPE_UPDATE = 2;
    const TYPE_DELETE = 3;
    const TYPE_LOGIN = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%operate_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_params', 'old_attr', 'new_attr'], 'string'],
            [['type', 'table', 'operated_at'], 'required'],
            [['type', 'user_id'], 'integer'],
            [['operated_at', 'created_at', 'updated_at'], 'safe'],
            [['request_url', 'memo'], 'string', 'max' => 255],
            [['request_method'], 'string', 'max' => 16],
            [['table'], 'string', 'max' => 128],
            [['server_ip', 'client_ip'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'request_url'    => '请求地址',
            'request_params' => '请求参数',
            'request_method' => '请求方法',
            'type'           => '操作类型',
            'table'          => '表名',
            'old_attr'       => '旧数据',
            'new_attr'       => '新数据',
            'server_ip'      => '服务器ip',
            'client_ip'      => '客服端ip',
            'user_id'        => '用户编号',
            'memo'           => '备注',
            'operated_at'    => '访问时间',
            'created_at'     => '创建时间',
            'updated_at'     => '修改时间',
        ];
    }
}
