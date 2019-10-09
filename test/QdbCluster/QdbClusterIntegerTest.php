<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterIntegerTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->integer();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->integer('alias', 0);
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
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
