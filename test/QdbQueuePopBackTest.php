<?php

require_once 'QdbTestBase.php';

class QdbQueuePopBackTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.popBack() too many args');
        $queue->popBack('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $queue = $this->cluster->getQueue('QdbQueue.popBack() not found');
        $queue->popBack();
    }

    public function testAfterPushBack()
    {
        $alias = 'QdbQueue.popBack() after QdbQueue.pushBack()';

        $queue = $this->cluster->getQueue($alias);

        $queue->pushBack('hello');
        $queue->pushBack('world');
        $result = $queue->popBack();

        $this->assertEquals('world', $result);
    }

    public function testAfterPushFront()
    {
        $alias = 'QdbQueue.popBack() after QdbQueue.pushFront()';

        $queue = $this->cluster->getQueue($alias);

        $queue->pushFront('hello');
        $queue->pushFront('world');
        $result = $queue->popBack();

        $this->assertEquals('hello', $result);
    }

    /**
     * @expectedException               QdbEmptyContainerException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmpty()
    {
        $alias = 'QdbQueue.popBack() empty';

        $queue = $this->cluster->getQueue($alias);

        $queue->pushBack('hello');
        $queue->popBack();
        $queue->popBack();
    }
}

?>
