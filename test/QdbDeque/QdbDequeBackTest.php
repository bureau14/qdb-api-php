<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbDequeBackTest extends QdbTestBase
{

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->back('i should not be there');
    }

    public function testAfterPushFront()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront('hello');
        $queue->pushFront('world');
        $this->assertEquals('hello', $queue->back());
    }

    public function testAfterPushBack()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $this->assertEquals('world', $queue->back());
    }

    /**
     * @expectedException               QdbContainerEmptyException
     */
    public function testOnEmptyQueue()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->popBack();
        $queue->back();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->back();
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->back();
    }
}

?>
