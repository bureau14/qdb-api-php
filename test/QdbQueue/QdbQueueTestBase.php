<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

abstract class QdbQueueTestBase extends QdbTestBase
{
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->getAlias());
        $this->queue = $this->cluster->queue($this->getAlias());
    }
}

?>
