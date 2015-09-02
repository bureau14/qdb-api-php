<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbDequePushBackTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $queue = $this->createEmptyQueue();

        $queue->pushBack(array());
    }

    public function testReturnValue()
    {
        $queue = $this->createEmptyQueue();

        $result = $queue->pushBack('hello');
        $this->assertEquals(null, $result);
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $queue = $this->createEmptyQueue($alias);

        $queue->pushBack('hello');
    }
}

?>
