<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueuePopBackTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->popBack('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->popBack();
    }

    public function testAfterPushBack()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $result = $queue->popBack();

        $this->assertEquals('world', $result);
    }

    public function testAfterPushFront()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront('hello');
        $queue->pushFront('world');
        $result = $queue->popBack();

        $this->assertEquals('hello', $result);
    }

    /**
     * @expectedException               QdbContainerEmptyException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmpty()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->popBack();
        $queue->popBack();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->popBack();
    }
}

?>
