<?php

require_once 'QdbTestBase.php';

class QdbClusterGetQueueTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->getQueue();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->getQueue('getQueue too many args', 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->getQueue(array());
    }

    public function testReturnType()
    {
        $queue = $this->cluster->getQueue('getQueue return type');
        $this->assertInstanceOf('QdbQueue', $queue);
    }

    public function testAlias()
    {
        $queue = $this->cluster->getQueue('getQueue test alias');
        $this->assertEquals('getQueue test alias', $queue->alias());
    }
}

?>
