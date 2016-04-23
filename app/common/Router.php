<?php
namespace Common;

use Phalcon\Di;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router as PhalconRouter;

class Router extends PhalconRouter
{
    /**
     * @var static
     */
    protected static $router;
    protected $_uriSource = self::URI_SOURCE_SERVER_REQUEST_URI;

    public function __construct($defaultRoutes = false)
    {
        parent::__construct($defaultRoutes);

        $routes = include ROOT_PATH . '/app/routes.php';
        foreach ($routes as $method => $methodRoutes) {
            $method == 'ANY' and $method = null;
            foreach ($methodRoutes as $uri => $handler) {
                if (is_string($handler)) {
                    list($controller, $action) = explode('::', $handler);
                    $handler = compact('controller', 'action');
                }
                $this->add($uri, $handler, $method);
            }
        }
    }

    public static function dispatch(Application $app)
    {
        $request = $app->handle();
        return $request->send();
    }

    public static function register(Di $di)
    {
        /* @var $dispatcher \Phalcon\Dispatcher */
        $dispatcher = $di->getShared('dispatcher');
        //设置不需要后缀
        $dispatcher->setActionSuffix('');
        $di->setShared('router', function () {
            $router = new Router(false);
            return $router;
        });
    }
}
