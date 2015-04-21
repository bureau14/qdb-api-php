<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterQueueTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->queue();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->queue($this->getAlias(), 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->queue(array());
    }

    public function testReturnType()
    {
        $queue = $this->cluster->queue($this->getAlias());
        $this->assertInstanceOf('QdbQueue', $queue);
    }

    public function testAlias()
    {
        $queue = $this->cluster->queue($this->getAlias());
        $this->assertEquals($this->getAlias(), $queue->alias());
    }
}

?>
