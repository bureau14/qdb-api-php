<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterEntryTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->entry();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->entry('alias', 0);
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $this->cluster->entry(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
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
