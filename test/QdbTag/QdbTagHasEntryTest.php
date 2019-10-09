<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagHasEntryTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $tag = $this->createEmptyTag();

        $tag->hasEntry();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $tag = $this->createEmptyTag();

        $tag->hasEntry('entry', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/entry/i');
        
        $tag = $this->createEmptyTag();

        $tag->hasEntry(array());
    }

    public function testWithString()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob()->alias();

        $this->assertFalse($tag->hasEntry($blob));

        $tag->attachEntry($blob);

        $this->assertTrue($tag->hasEntry($blob));
    }

    public function testWithQdbEntry()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();

        $this->assertFalse($tag->hasEntry($blob));

        $tag->attachEntry($blob);

        $this->assertTrue($tag->hasEntry($blob));
    }
}
?>
