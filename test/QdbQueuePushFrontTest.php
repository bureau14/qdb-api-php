<?php

require_once 'QdbTestBase.php';

class QdbQueuePushFrontTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushFront() not enough args');
        $queue->pushFront();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushFront() too many args');
        $queue->pushFront('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushFront() wrong type');
        $queue->pushFront(array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testPutAfter()
    {
        $alias = 'QdbQueue.pushFront() then QdbCluster.put()';

        $queue = $this->cluster->getQueue($alias);
        $queue->pushFront('hello');

        $this->cluster->put($alias, 'world');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testPutBefore()
    {
        $alias = 'QdbCluster.put() then QdbQueue.pushFront()';

        $this->cluster->put($alias, 'world');

        $queue = $this->cluster->getQueue($alias);
        $queue->pushFront('hello');
    }
}

?>
