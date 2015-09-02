<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbDequeFrontTest extends QdbTestBase
{

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->front('i should not be there');
    }

    public function testAfterPushFront()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront('hello');
        $queue->pushFront('world');
        $this->assertEquals('world', $queue->front());
    }

    public function testAfterPushBack()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $this->assertEquals('hello', $queue->front());
    }

    /**
     * @expectedException               QdbContainerEmptyException
     */
    public function testOnEmptyQueue()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->popBack();
        $queue->front();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->front();
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->front();
    }
}

?>
