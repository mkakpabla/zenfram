<?php


namespace Framework\Commands\Phinx;

class SeedRun extends \Phinx\Console\Command\SeedRun
{
    protected function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub
        $this->setName('phinx:seed:run');
    }
}