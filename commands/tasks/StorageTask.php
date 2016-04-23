<?php
namespace Commands\Tasks;

use Commands\BaseTask;

class StorageTask extends BaseTask
{
    protected $types = array('cache', 'profiler');

    public function mainAction()
    {
        $this->helpAction();
    }

    public function helpAction()
    {
        $types = implode(' | ', $this->types);
        echo " Clear storage files.\n php tool storage clear [ {$types} ]\n\n";
    }

    public function clearAction(array $params = null)
    {
        $type = isset($params[0]) ? $params[0] : 'cache';
        if (!in_array($type, $this->types)) {
            $this->helpAction();
            return;
        }
        $path = ROOT_PATH . "/storage/{$type}/";
        if (is_writeable($path)) {
            $skipFiles = array('.', '..', '.gitkeep');
            if (is_dir($path) && ($dir = opendir($path))) {
                echo "\n";
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
            echo "\n Clear storage {$type} success! \n\n";
        } else {
            echo "\n Clear storage failed, please use 'sudo php tool storage clear' \n\n";
        }
    }

}
