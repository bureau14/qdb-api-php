<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterTagTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->tag();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->tag('alias', 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
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
