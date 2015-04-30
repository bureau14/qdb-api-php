<?php

abstract class QdbTestBase extends PHPUnit_Framework_TestCase
{
    protected $cluster;
    protected $alias;

    protected function setUp()
    {
        $this->cluster = new QdbCluster('qdb://127.0.0.1:20552/');
        $this->alias = get_class($this) . '::' . $this->getName();
    }
}

?>
