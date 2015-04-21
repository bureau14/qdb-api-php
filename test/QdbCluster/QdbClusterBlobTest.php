<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterBlobTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->blob();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->blob($this->getAlias(), 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->blob(array());
    }

    public function testReturnType()
    {
        $blob = $this->cluster->blob($this->getAlias());
        $this->assertInstanceOf('QdbBlob', $blob);
    }

    public function testAlias()
    {
        $blob = $this->cluster->blob($this->getAlias());
        $this->assertEquals($this->getAlias(), $blob->alias());
    }
}

?>
