<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchTestBase extends QdbTestBase
{
    protected $queue;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->getAlias());
        $this->batch = new QdbBatch();
    }
}

?>
