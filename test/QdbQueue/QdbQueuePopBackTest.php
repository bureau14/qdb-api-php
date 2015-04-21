<?php

require_once 'QdbQueueTestBase.php';

class QdbQueuePopBackTest extends QdbQueueTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->popBack('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /cannot be found/i
     */
    public function testNotFound()
    {
        $this->queue->popBack();
    }

    public function testAfterPushBack()
    {
        $this->queue->pushBack('hello');
        $this->queue->pushBack('world');
        $result = $this->queue->popBack();

        $this->assertEquals('world', $result);
    }

    public function testAfterPushFront()
    {
        $this->queue->pushFront('hello');
        $this->queue->pushFront('world');
        $result = $this->queue->popBack();

        $this->assertEquals('hello', $result);
    }

    /**
     * @expectedException               QdbEmptyContainerException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmpty()
    {
        $this->queue->pushBack('hello');
        $this->queue->popBack();
        $this->queue->popBack();
    }
}

?>
