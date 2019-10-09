<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterTagTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->tag();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->tag('alias', 0);
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $this->cluster->tag(array());
    }

    public function testReturnType()
    {
        $alias = createUniqueAlias();

        $tag = $this->cluster->tag($alias);
        $this->assertInstanceOf('QdbTag', $tag);
        $this->assertInstanceOf('QdbEntry', $tag);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();

        $tag = $this->cluster->tag($alias);
        $this->assertEquals($alias, $tag->alias());
    }
}

?>
