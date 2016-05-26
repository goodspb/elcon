<?php
namespace Commands;

use Symfony\Component\Console\Application as SymfonyApplication;
use Phalcon\Di\FactoryDefault\Cli as CliDI;

class Application extends SymfonyApplication
{
    protected $di;

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param mixed $di
     */
    public function setDi(CliDI $di)
    {
        $this->di = $di;
    }
}