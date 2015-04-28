<?php

require_once 'QdbQueueTestBase.php';

class QdbQueueFrontTest extends QdbQueueTestBase
{

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->front('i should not be there');
    }

    public function testAfterPushFront()
    {
        $this->queue->pushFront('hello');
        $this->queue->pushFront('world');
        $this->assertEquals('world', $this->queue->front());
    }

    public function testAfterPushBack()
    {
        $this->queue->pushBack('hello');
        $this->queue->pushBack('world');
        $this->assertEquals('hello', $this->queue->front());
    }

    /**
     * @expectedException               QdbEmptyContainerException
     */
    public function testOnEmptyQueue()
    {
        $this->queue->pushBack('hello');
        $this->queue->popBack();
        $this->queue->front();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testOnBlob()
    {
        $this->blob->put('world');
        $this->queue->front();
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $this->queue->front();
    }
}

?>
