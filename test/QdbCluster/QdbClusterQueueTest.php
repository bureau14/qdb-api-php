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
        $this->cluster->queue('alias', 0);
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
        $alias = createUniqueAlias();

        $queue = $this->cluster->queue($alias);

        $this->assertInstanceOf('QdbQueue', $queue);
        $this->assertInstanceOf('QdbEntry', $queue);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();

        $queue = $this->cluster->queue($alias);

        $this->assertEquals($alias, $queue->alias());
    }
}

?>
