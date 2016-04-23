<?php
namespace Commands\Tasks;

use Common\Exception\AjaxReturnException;
use Common\Exception\NotFoundException;
use Common\Library\LogHelper;
use Commands\BaseTask;
use Phalcon\Di;
use Phalcon\Loader;
use swoole_server;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Common\Phalcon\Dispatcher;
use Phalcon\Mvc\Application;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Http\Request;
use Phalcon\Mvc\Router;
use Phalcon\Http\ResponseInterface;
use Phalcon\Http\Response;

class ServiceTask extends BaseTask
{
    /**
     * @var swoole_server
     */
    protected $swoole;
    protected $swoolePort;
    protected $availablePorts = array(9510, 9511);
    protected $config;
    protected $debug = false;
    protected $runDir = '/tmp/pay/';
    protected $linuxServiceScript = '/etc/init.d/pay';
    protected $delaySave;
    protected $pid;
    protected $managerPid;
    protected $sockFile;
    /** @var  \Phalcon\Mvc\Application */
    protected $application;
    /** @var  \Phalcon\Di */
    protected $di;


    public function mainAction()
    {
        echo " Under develop! ";
    }

}
