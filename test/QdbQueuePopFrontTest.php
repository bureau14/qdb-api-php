<?php

require_once 'QdbTestBase.php';

class QdbQueuePopFrontTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.popFront() too many args');
        $queue->popFront('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->cluster->getQueue('QdbQueue.popFront() not found');
        $queue->popFront();
    }

    public function testAfterPushBack()
    {
        $alias = 'QdbQueue.popFront() after QdbQueue.pushBack()';

        $queue = $this->cluster->getQueue($alias);

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $result = $queue->popFront();

        $this->assertEquals('hello', $result);
    }

    public function testAfterPushFront()
    {
        $alias = 'QdbQueue.popFront() after QdbQueue.pushFront()';

        $queue = $this->cluster->getQueue($alias);

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
        $alias = 'QdbQueue.popFront() empty';

        $queue = $this->cluster->getQueue($alias);

        $queue->pushBack('hello');
        $queue->popFront();
        $queue->popFront();
    }
}

?>
