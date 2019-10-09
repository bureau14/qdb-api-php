<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagDetachEntryTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $tag = $this->createEmptyTag();

        $tag->detachEntry();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $tag = $this->createEmptyTag();

        $tag->detachEntry('entry', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/entry/i');
        
        $tag = $this->createEmptyTag();

        $tag->detachEntry(array());
    }

    public function testNonExistingEntry()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $tag = $this->createEmptyTag();

        $tag->detachEntry('entry');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $tag = $this->createEmptyTag($alias);
        $blob = $this->createBlob($alias);

        $tag->detachEntry($alias);
    }

    public function testWithString()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob()->alias();
        $tag->attachEntry($blob);

        $this->assertTrue($tag->detachEntry($blob));
        $this->assertFalse($tag->detachEntry($blob));
    }

    public function testWithQdbEntry()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();
        $tag->attachEntry($blob);

        $this->assertTrue($tag->detachEntry($blob));
        $this->assertFalse($tag->detachEntry($blob));
    }
}

?>
