<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

abstract class QdbHashSetTestBase extends QdbTestBase
{
    protected $hashSet;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->alias);
        $this->hashSet = $this->cluster->hashSet($this->alias);
    }
}

?>
