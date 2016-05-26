<?php
namespace Commands\Console;

use Commands\Command;
use Commands\Migration;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{

    protected $tableName = 'migration';
    protected $migrationPath = ROOT_PATH.'/migration/';
    protected $migrationExt = '.php';
    protected $migratedList = [];

    protected function configure()
    {
        $this
            ->setName('database:migrate')
            ->setDescription('Migration of database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createMigrationTable();
        $this->migrate($output);
        if (empty($this->migratedList)) {
            $output->writeln($this->comment("Nothing to migrate!"));
        } else {
            $output->writeln($this->info("Update complete!"));
        }
    }

    protected function createMigrationTable()
    {
        $this->db()->execute("
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

    protected function migrate($output)
    {
        $realPath = $this->migrationPath."/*".$this->migrationExt;
        $files = glob($realPath);
        if ($files) foreach ($files as $file) {
            if (!is_file($file)) continue;
            $filename = pathinfo($file, PATHINFO_FILENAME);
            if (!($migrationDb = $this->readMigrationFromDb($filename))) {
                $this->runMigrateFile($file,$filename, $output);
                $this->migratedList[] = $filename;
                $this->insertMigration($filename);
            }
        }
    }

    protected function runMigrateFile($file, $filename, $output)
    {
        $db = $this->db();
        $db->begin();
        try {
            include $file;
            $db->commit();
            echo sprintf("\n Processing: \"%s\" ", $filename);
        } catch (\Exception $e) {
            $db->rollback();
            $output->writeln($this->error("\n".$e->getCode() . ' : ' . $e->getMessage()."\n\n"));
            exit();
        }
        return $this;
    }

}
