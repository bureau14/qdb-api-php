<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagdetachEntryTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->detachEntry();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->detachEntry('entry', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /entry/i
     */
    public function testInvalidArgument()
    {
        $tag = $this->createEmptyTag();

        $tag->detachEntry(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testNonExistingEntry()
    {
        $tag = $this->createEmptyTag();

        $tag->detachEntry('entry');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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
