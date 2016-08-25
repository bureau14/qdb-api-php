<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagHasEntryTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->hasEntry();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->hasEntry('entry', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /entry/i
     */
    public function testInvalidArgument()
    {
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
