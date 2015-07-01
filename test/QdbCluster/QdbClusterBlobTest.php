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
        $this->cluster->blob('alias', 0);
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
        $blob = $this->cluster->blob(createUniqueAlias());
        $this->assertInstanceOf('QdbBlob', $blob);
        $this->assertInstanceOf('QdbExpirableEntry', $blob);
        $this->assertInstanceOf('QdbEntry', $blob);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();
        $blob = $this->cluster->blob($alias);
        $this->assertEquals($alias, $blob->alias());
    }
}

?>
