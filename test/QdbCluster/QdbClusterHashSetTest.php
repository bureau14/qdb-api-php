<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterHashSetTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->hashSet();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->hashSet($this->alias, 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->hashSet(array());
    }

    public function testReturnType()
    {
        $hashSet = $this->cluster->hashSet($this->alias);
        $this->assertInstanceOf('QdbHashSet', $hashSet);
        $this->assertInstanceOf('QdbEntry', $hashSet);
    }

    public function testAlias()
    {
        $hashSet = $this->cluster->hashSet($this->alias);
        $this->assertEquals($this->alias, $hashSet->alias());
    }
}

?>
