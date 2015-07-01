<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagRemoveEntryTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->removeEntry();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->removeEntry('entry', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /entry/i
     */
    public function testInvalidArgument()
    {
        $tag = $this->createEmptyTag();

        $tag->removeEntry(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testNonExistingEntry()
    {
        $tag = $this->createEmptyTag();

        $tag->removeEntry('entry');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $tag = $this->createEmptyTag($alias);
        $blob = $this->createBlob($alias);

        $tag->removeEntry($alias);
    }

    public function testWithString()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob()->alias();
        $tag->addEntry($blob);

        $this->assertTrue($tag->removeEntry($blob));
        $this->assertFalse($tag->removeEntry($blob));
    }

    public function testWithQdbEntry()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();
        $tag->addEntry($blob);

        $this->assertTrue($tag->removeEntry($blob));
        $this->assertFalse($tag->removeEntry($blob));
    }
}

?>
