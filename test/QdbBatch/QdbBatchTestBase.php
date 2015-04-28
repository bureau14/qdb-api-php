<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

abstract class QdbBatchTestBase extends QdbTestBase
{
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->alias);
        $this->batch = new QdbBatch();
    }
}

?>
