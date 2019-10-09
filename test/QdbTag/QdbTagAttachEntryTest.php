<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagAttachEntryTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $tag = $this->createEmptyTag();

        $tag->attachEntry();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $tag = $this->createEmptyTag();

        $tag->attachEntry('entry', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/entry/i');
        
        $tag = $this->createEmptyTag();

        $tag->attachEntry(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        $this->expectExceptionMessageRegExp('/found/i');
        
        $tag = $this->createEmptyTag();

        $tag->attachEntry('entry');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob($tag->alias());

        $tag->attachEntry(createUniqueAlias());
    }

    public function testWithString()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob()->alias();

        $this->assertTrue($tag->attachEntry($blob));
        $this->assertFalse($tag->attachEntry($blob));
    }

    public function testWithQdbEntry()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();

        $this->assertTrue($tag->attachEntry($blob));
        $this->assertFalse($tag->attachEntry($blob));
    }
}

?>
