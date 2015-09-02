<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbDequeRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->remove('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $queue = $this->createEmptyQueue();

        $queue->remove();
    }

    public function testPushRemovePush()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('first');
        $queue->remove();
        $queue->pushBack('second');

        $this->assertEquals('second', $queue->popFront());
    }
}

?>
