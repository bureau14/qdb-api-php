<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterDequeTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->deque();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->deque('alias', 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->deque(array());
    }

    public function testReturnType()
    {
        $alias = createUniqueAlias();

        $deque = $this->cluster->deque($alias);

        $this->assertInstanceOf('QdbDeque', $deque);
        $this->assertInstanceOf('QdbEntry', $deque);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();

        $deque = $this->cluster->deque($alias);

        $this->assertEquals($alias, $deque->alias());
    }
}

?>
