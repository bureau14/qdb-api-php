<?php

require_once 'QdbQueueTestBase.php';

class QdbQueuePopFrontTest extends QdbQueueTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->popFront('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $this->queue->popFront();
    }

    public function testAfterPushBack()
    {
        $this->queue->pushBack('hello');
        $this->queue->pushBack('world');
        $result = $this->queue->popFront();

        $this->assertEquals('hello', $result);
    }

    public function testAfterPushFront()
    {
        $this->queue->pushFront('hello');
        $this->queue->pushFront('world');
        $result = $this->queue->popFront();

        $this->assertEquals('world', $result);
    }

    /**
     * @expectedException               QdbEmptyContainerException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmpty()
    {
        $this->queue->pushBack('hello');
        $this->queue->popFront();
        $this->queue->popFront();
    }
}

?>
