<?php

use yii\db\Migration;

class m190520_152120_create_operate_log_table extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL
CREATE TABLE `operate_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求地址',
  `request_params` text COMMENT '请求参数',
  `request_method` varchar(16) NOT NULL DEFAULT '' COMMENT '请求方法',
  `type` tinyint(3) NOT NULL COMMENT '操作类型1-新增2-修改3-删除4-登录',
  `table` varchar(128) NOT NULL COMMENT '表名',
  `old_attr` text COMMENT '旧数据',
  `new_attr` text COMMENT '新数据',
  `server_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '服务器ip',
  `client_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '客服端ip',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户编号',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `operated_at` timestamp NOT NULL COMMENT '访问时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `idx_request_url_method` (`request_url`, `request_method`),
  KEY `idx_operated_at` (`operated_at`),
  KEY `idx_created_at` (`created_at`)
) ENGINE = InnoDB COMMENT '操作记录表';
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('operate_log');
    }
}