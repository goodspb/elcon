<?php
namespace Commands\Console;

use Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StorageCommand extends Command
{
    protected $types = array('cache', 'profiler', 'templates');

    protected function configure()
    {
        $this
            ->setName('storage:clear')
            ->setDescription('clear the storage dir: all | '.implode(' | ', $this->types))
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'which type of storage you want to clear ?',
                'all'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input = $input->getArgument('type');

        if ($input != 'all' && !in_array($input, $this->types)) {
            $output->writeln($this->error('do not have this type of storage.'));
            return;
        }
        foreach ($this->types as $counter => $type) {
            if ($input == 'all' || $type == $input) {
                $this->clearCache($type, $output);
            }
        }
        $output->writeln($this->comment(" storage clear success "));
    }

    protected function clearCache($type, OutputInterface $output)
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
                            $output->writeln($this->error(" delete fail : " . $resource));
                        }
                    }
                }
                closedir($dir);
            }
            $output->writeln($this->info("Clear storage {$type} success!"));
        } else {
            $output->writeln($this->error( "Clear storage failed, please use 'sudo php tool storage clear' "));
        }
    }

}
