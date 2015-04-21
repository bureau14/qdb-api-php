<?php

abstract class QdbTestBase extends PHPUnit_Framework_TestCase
{
    protected $cluster;

    protected function setUp()
    {
        $nodes = array(
            array(
                'address' => '127.0.0.1',
                'port' => 20552
            )
        );
        $this->cluster = new QdbCluster($nodes);
    }

    protected function getAlias()
    {
        return get_class($this) . '::' . $this->getName();
    }
}

?>
