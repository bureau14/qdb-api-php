<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterIntegerTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->integer();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->integer('alias', 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->integer(array());
    }

    public function testReturnType()
    {
        $integer = $this->cluster->integer(createUniqueAlias());

        $this->assertInstanceOf('QdbInteger', $integer);
        $this->assertInstanceOf('QdbExpirableEntry', $integer);
        $this->assertInstanceOf('QdbEntry', $integer);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();

        $integer = $this->cluster->integer($alias);

        $this->assertEquals($alias, $integer->alias());
    }
}

?>
