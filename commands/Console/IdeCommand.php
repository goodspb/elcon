<?php
namespace Commands\Console;

use Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IdeCommand extends Command
{
    private $ideHelperFile = '_ide_helper.php';
    private $ideHelperPath = ROOT_PATH .'/';

    protected function configure()
    {
        $this->setName('ide:create')
             ->setDescription('Create '.$this->ideHelperFile)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ideHelper = $this->ideHelperPath.$this->ideHelperFile;
        $content = "<?php\n";
        $content .= $this->getCommonAlias();
        file_put_contents($ideHelper, $content);
        $output->writeln($this->info("Create ide helper success."));
    }

    private function getCommonAlias()
    {
        $content = '';
        $aliases = $this->config('aliases');
        if ($aliases) foreach ($aliases as $alias => $class) {
            $content .= "class {$alias} extends $class{}\n";
        }
        return $content;
    }

}
