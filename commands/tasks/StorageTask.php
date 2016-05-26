<?php
namespace Commands\Tasks;

use Commands\BaseTask;

class StorageTask extends BaseTask
{
    protected $types = array('cache', 'profiler', 'templates');

    public function mainAction()
    {
        $this->helpAction();
    }

    public function helpAction()
    {
        $types = implode(' | ', $this->types);
        echo "Usage: \n php tool storage clear [ {$types} ]\n\n";
    }

    public function clearAction(array $params = null)
    {
        $input = isset($params[0]) ? $params[0] : 'all';
        if ($input != 'all' && !in_array($input, $this->types)) {
            $this->helpAction();
            return;
        }
        foreach ($this->types as $counter => $type) {
            if ($input == 'all' || $type == $input) {
                $this->clearCache($type);
            }
            if ($counter == (count($this->types)-1)) echo "\n";
        }
    }

    protected function clearCache($type)
    {
        $path = ROOT_PATH . "/storage/{$type}/";
        if (is_writeable($path)) {
            $skipFiles = array('.', '..', '.gitkeep');
            if (is_dir($path) && ($dir = opendir($path))) {
                while (($resource = readdir($dir)) !== false) {
                    $filePath = $path . $resource;
                    if (!in_array($resource, $skipFiles) && is_file($filePath)) {
                        if (unlink($filePath)) {
                        } else {
                            echo " [ delete fail ] ", $resource . "\n";
                        }
                    }
                }
                closedir($dir);
            }
            echo "\n Clear storage {$type} success! \n";
        } else {
            echo "\n Clear storage failed, please use 'sudo php tool storage clear' \n";
        }
    }

}
