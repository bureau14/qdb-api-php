<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbDequeSizeTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->size('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->size();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatible()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->size();
    }

    public function testEmptyQueue()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront("hello");
        $queue->popFront();

        $this->assertEquals(0, $queue->size());
    }

    public function testAfterPush()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront("hello");
        $queue->pushBack("world");

        $this->assertEquals(2, $queue->size());
    }
}

?>
