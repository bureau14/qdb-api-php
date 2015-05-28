<?php

require_once 'QdbQueueTestBase.php';

class QdbQueueRemoveTest extends QdbQueueTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->remove('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->queue->remove();
    }

    public function testPushRemovePush()
    {
        $this->queue->pushBack('first');
        $this->queue->remove();
        $this->queue->pushBack('second');

        $this->assertEquals('second', $this->queue->popFront());
    }
}

?>
