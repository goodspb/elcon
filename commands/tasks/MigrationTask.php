<?php
namespace Commands\Tasks;

use Commands\BaseTask;
use Commands\Migration;
use Exception;

class MigrationTask extends BaseTask
{

    protected $tableName = 'migration';
    protected $migrationPath = ROOT_PATH.'/migration/';
    protected $migrationExt = '.php';
    protected $migratedList = array();

    public function mainAction()
    {
        $this->createMigrationTable();
        $this->migrate();
        if (empty($this->migratedList)) {
            echo "\n Nothing to migrate! \n\n";
        } else {
            echo "\n Update complete! \n\n";
        }
    }

    protected function createMigrationTable()
    {
        $this->getDB()->execute("
            CREATE TABLE IF NOT EXISTS `migration` (
                `id` int(10) unsigned Not NULL AUTO_INCREMENT,
                `filename` varchar(255) NOT NULL,
                `addtime` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    protected function readMigrationFromDb($file)
    {
        return Migration::getByFilename($file);
    }

    protected function insertMigration($file)
    {
        return (new Migration())->addItem($file);
    }

    protected function migrate()
    {
        $realPath = $this->migrationPath."/*".$this->migrationExt;
        $files = glob($realPath);
        if ($files) foreach ($files as $file) {
            if (!is_file($file)) continue;
            $filename = pathinfo($file, PATHINFO_FILENAME);
            if (!($migrationDb = $this->readMigrationFromDb($filename))) {
                $this->runMigrateFile($file,$filename);
                $this->migratedList[] = $filename;
                $this->insertMigration($filename);
            }
        }
    }

    protected function runMigrateFile($file, $filename)
    {
        $db = $this->getDB();
        $db->begin();
        try {
            include $file;
            $db->commit();
            echo sprintf("\n Processing: \"%s\" ", $filename);
        } catch (\Exception $e) {
            $db->rollback();
            echo "\n".$e->getCode() . ' : ' . $e->getMessage()."\n\n";
//            echo $e->getTraceAsString();
            exit();
        }
        return $this;
    }

}
