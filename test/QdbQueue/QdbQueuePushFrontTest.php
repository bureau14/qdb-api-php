<?php

require_once 'QdbQueueTestBase.php';

class QdbQueuePushFrontTest extends QdbQueueTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->queue->pushFront();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->pushFront('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->queue->pushFront(array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testPutAfter()
    {
        $this->queue->pushFront('hello');
        $this->blob->put('world');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testPutBefore()
    {
        $this->blob->put('world');
        $this->queue->pushFront('hello');
    }
}

?>
