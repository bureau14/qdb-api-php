<?php

require_once 'QdbQueueTestBase.php';

class QdbQueuePushBackTest extends QdbQueueTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->queue->pushBack();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->queue->pushBack('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->queue->pushBack(array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testPutAfter()
    {
        $this->queue->pushBack('hello');
        $this->blob->put('world');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testPutBefore()
    {
        $this->blob->put('world');
        $this->queue->pushBack('hello');
    }
}

?>
