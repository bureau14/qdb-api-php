<?php

require_once 'QdbTestBase.php';

class QdbQueuePushBackTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushBack() not enough args');
        $queue->pushBack();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushBack() too many args');
        $queue->pushBack('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $queue = $this->cluster->getQueue('QdbQueue.pushBack() wrong type');
        $queue->pushBack(array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testPutAfter()
    {
        $alias = 'QdbQueue.pushBack() then QdbCluster.put()';

        $queue = $this->cluster->getQueue($alias);
        $queue->pushBack('hello');

        $this->cluster->put($alias, 'world');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testPutBefore()
    {
        $alias = 'QdbCluster.put() then QdbQueue.pushBack()';

        $this->cluster->put($alias, 'world');

        $queue = $this->cluster->getQueue($alias);
        $queue->pushBack('hello');
    }
}

?>
