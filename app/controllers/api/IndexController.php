<?php
namespace Api\Controllers;

use Controllers\BaseController;
use Common\Lang;

class IndexController extends BaseController
{

    public function getIndex()
    {
        $this->cookie('aax', 'xxx', 86400 + time(), '/');
//        return $this->jsonReturn(["a"=> $this->lang('text')]);
        echo 'Welcome to phaclon';
    }

    public function getView()
    {
        $this->view("api/index");
    }

}
