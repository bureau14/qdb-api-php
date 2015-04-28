<?php

require_once 'QdbQueueTestBase.php';

class QdbQueueBackTest extends QdbQueueTestBase
{

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->back('i should not be there');
    }

    public function testAfterPushFront()
    {
        $this->queue->pushFront('hello');
        $this->queue->pushFront('world');
        $this->assertEquals('hello', $this->queue->back());
    }

    public function testAfterPushBack()
    {
        $this->queue->pushBack('hello');
        $this->queue->pushBack('world');
        $this->assertEquals('world', $this->queue->back());
    }

    /**
     * @expectedException               QdbEmptyContainerException
     */
    public function testOnEmptyQueue()
    {
        $this->queue->pushBack('hello');
        $this->queue->popBack();
        $this->queue->back();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testOnBlob()
    {
        $this->blob->put('world');
        $this->queue->back();
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $this->queue->back();
    }
}

?>
