<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTagGetEntriesTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $tag = $this->createEmptyTag();

        $tag->getEntries('i should not be there');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $tag = $this->createEmptyTag($alias);
        $blob = $this->createBlob($alias);

        $tag->getEntries();
    }

    public function testEmpty()
    {
        $tag = $this->createEmptyTag();

        $result = $tag->getEntries();

        $this->assertCount(0, $result);
    }

    public function testOneBlob()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();

        $tag->addEntry($blob);
        $entries = $tag->getEntries();

        $entry = $this->getSingleElement($entries);
        $this->assertInstanceOf('QdbBlob', $entry);
        $this->assertEquals($blob->alias(), $entry->alias());
    }

    public function testOneHashSet()
    {
        $tag = $this->createEmptyTag();
        $hashSet = $this->createHashSet();

        $tag->addEntry($hashSet);
        $entries = $tag->getEntries();

        $entry = $this->getSingleElement($entries);
        $this->assertInstanceOf('QdbHashSet', $entry);
        $this->assertEquals($hashSet->alias(), $entry->alias());
    }

    public function testOneInteger()
    {
        $tag = $this->createEmptyTag();
        $integer = $this->createInteger();

        $tag->addEntry($integer);
        $entries = $tag->getEntries();

        $entry = $this->getSingleElement($entries);
        $this->assertInstanceOf('QdbInteger', $entry);
        $this->assertEquals($integer->alias(), $entry->alias());
    }

    public function testOneQueue()
    {
        $tag = $this->createEmptyTag();
        $queue = $this->createQueue();

        $tag->addEntry($queue);
        $entries = $tag->getEntries();

        $entry = $this->getSingleElement($entries);
        $this->assertInstanceOf('QdbDeque', $entry);
        $this->assertEquals($queue->alias(), $entry->alias());
    }

    public function testOneTag()
    {
        $tag1 = $this->createEmptyTag();
        $tag2 = $this->createTag();

        $tag1->addEntry($tag2);
        $entries = $tag1->getEntries();

        $entry = $this->getSingleElement($entries);
        $this->assertInstanceOf('QdbTag', $entry);
        $this->assertEquals($tag2->alias(), $entry->alias());
    }

    private function getSingleElement($traversable)
    {
        $theElement = null;
        foreach ($traversable as $element) {
            $this->assertNull($theElement);
            $theElement = $element;
        }
        $this->assertNotNull($theElement);
        return $theElement;
    }
}

?>
