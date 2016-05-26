<?php
namespace Commands\Tasks;

use Commands\BaseTask;
use \Config;

class IdeTask extends BaseTask
{
    private $ideHelperFile = '_ide_helper.php';
    private $ideHelperPath = ROOT_PATH .'/';

    public function mainAction()
    {
        $ideHelper = $this->ideHelperPath.$this->ideHelperFile;
        $content = "<?php\n";
        $content .= $this->getCommonAlias();
        file_put_contents($ideHelper, $content);
        echo "Create ide helper success.\n";
    }

    private function getCommonAlias()
    {
        $content = '';
        $aliases = Config::get('aliases');
        if ($aliases) foreach ($aliases as $alias => $class) {
            $content .= "class {$alias} extends $class{}\n";
        }
        return $content;
    }

}
