<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerTestBase extends QdbTestBase
{
    protected $integer;

    public function setUp()
    {
        parent::setUp();
        $this->blob = $this->cluster->blob($this->getAlias());
        $this->integer = $this->cluster->integer($this->getAlias());
    }
}

?>
