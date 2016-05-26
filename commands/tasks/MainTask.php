<?php
namespace Commands\Tasks;

use Commands\BaseTask;

class MainTask extends BaseTask
{
    public function mainAction()
    {
        echo "\nUsage:\n\n";
        echo "1. php tool migration [ 更新数据库 ] \n\n";
        echo "2. php tool storage clear [ all | cache | profiler | templates ] \n\n";
        echo "3. php tool service [ 服务模式 ] ( under develop ) \n\n";
    }
}
