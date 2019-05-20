<?php

namespace xlerr\operatelog;

use Composer\Script\Event;

class Installer
{
    public static function install(Event $event)
    {
        $src = __DIR__ . '/migrations/m190520_152120_create_operate_log_table.php';
        $disc = './console/migrations/m190520_152120_create_operate_log_table.php';
        copy($src, $disc);
    }
}