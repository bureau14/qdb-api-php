<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

abstract class QdbQueueTestBase extends QdbTestBase
{
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->alias);
        $this->queue = $this->cluster->queue($this->alias);
    }
}

?>
