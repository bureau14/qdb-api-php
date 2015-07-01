<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueuePopFrontTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->popFront('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->popFront();
    }

    public function testAfterPushBack()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $result = $queue->popFront();

        $this->assertEquals('hello', $result);
    }

    public function testAfterPushFront()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushFront('hello');
        $queue->pushFront('world');
        $result = $queue->popFront();

        $this->assertEquals('world', $result);
    }

    /**
     * @expectedException               QdbEmptyContainerException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmpty()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello');
        $queue->popFront();
        $queue->popFront();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->popFront();
    }
}

?>
