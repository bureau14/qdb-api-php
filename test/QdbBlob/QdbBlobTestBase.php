<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobTestBase extends QdbTestBase
{
    protected $blob;

    public function setUp()
    {        
        parent::setUp();
        $this->blob = $this->cluster->blob($this->getAlias());
    }
}

?>
