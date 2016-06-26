<?php
namespace Commands\Tasks;

use Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Common\Log;
use Library\Cron\TdCron;
use Common\Model\Cron;

class CronTask extends Command
{
    protected $initializedJobs;
    protected $jobs;
    protected $now;

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->now = strtotime(date('Y-n-j H:i'));
        $this->cron();
    }


    public function cron()
    {
        $db = $this->db();
        restore_error_handler();
        restore_exception_handler();
        $this->initializedJobs = array();
        $jobs = $db->fetchAll("select * from `cron` where status = 'initialized'");

        /**
         * @var $cron Cron
         * 已存在 cron (initialized 状态)
         */
        if ($jobs) {
            $cron = new Cron();
            foreach ($jobs as $data) {
                $cron->setData($data);
                $this->initializedJobs[$data['name']] = $cron;
            }
        }

        /**
         * 新 cron
         */
        foreach ($this->getCronJobs() as $name => $cronJob) {
            if (isset($cronJob['expression'])) {
                $expression = $cronJob['expression'];
            } else {
                Log::log(Log::WARNING, 'Cron expression is required for cron job "' . $name . '"');
                continue;
            }
            if ($this->now != TdCron::getNextOccurrence($expression, $this->now)) {
                continue;
            }
            $cronJob['name'] = $name;
            $cron = isset($this->initializedJobs[$name]) ? $this->initializedJobs[$name] : $this->initializedJobs[$name] = new Cron();
            $cron->cronInitialize((array)$cronJob);
        }

        /* @var $cron Cron 处理 */
        foreach ($this->initializedJobs as $cron) {
            $cron->run();
        }

    }


    /**
     * Get All Defined Cron Jobs
     * 获取配置
     * @return array
     */
    public function getCronJobs()
    {
        if ($this->jobs === null) {
            $this->jobs = (array)$this->config('cron.cron');
        }
        return $this->jobs;
    }
}
