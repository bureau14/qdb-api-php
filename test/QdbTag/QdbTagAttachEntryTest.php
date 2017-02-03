<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagAttachEntryTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->attachEntry();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->attachEntry('entry', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /entry/i
     */
    public function testInvalidArgument()
    {
        $tag = $this->createEmptyTag();

        $tag->attachEntry(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $tag = $this->createEmptyTag();

        $tag->attachEntry('entry');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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
