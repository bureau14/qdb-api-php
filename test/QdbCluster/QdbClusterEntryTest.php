<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterEntryTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->entry();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->entry('alias', 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->entry(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->cluster->entry(createUniqueAlias());
    }

    public function testBlob()
    {
        $blob = $this->createBlob();
        $entry = $this->cluster->entry($blob->alias());
        $this->assertInstanceOf('QdbBlob', $entry);
    }

    public function testInteger()
    {
        $integer = $this->createInteger();
        $entry = $this->cluster->entry($integer->alias());
        $this->assertInstanceOf('QdbInteger', $entry);
    }

    public function testTag()
    {
        $tag = $this->createTag();
        $entry = $this->cluster->entry($tag->alias());
        $this->assertInstanceOf('QdbTag', $entry);
    }
}

?>
