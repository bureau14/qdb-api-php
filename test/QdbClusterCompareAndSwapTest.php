<?php

require_once 'QdbTestBase.php';

class QdbClusterCompareAndSwapTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->compareAndSwap('compare_and_swap not enough', 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->compareAndSwap('compare_and_swap too many', 'content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->compareAndSwap(array(), 'content', 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->cluster->compareAndSwap("compare_and_swap alias", array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->cluster->compareAndSwap("compare_and_swap alias", 'content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->compareAndSwap("compare_and_swap alias", 'content', 'comparand', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->cluster->compareAndSwap('compare_and_swap not found', 'content', 'comparand');
    }

    public function testMatching()
    {
        $alias = 'compare_and_swap matching';

        $this->cluster->put($alias, 'first');

        $result = $this->cluster->compareAndSwap($alias, 'second', 'first');

        $this->assertEquals('first', $result);
        $this->assertEquals('second', $this->cluster->get($alias));
    }

    public function testRemoveNotMatching()
    {
        $alias = 'compare_and_swap not matching';

        $this->cluster->put($alias, 'first');

        $result = $this->cluster->compareAndSwap($alias, 'second', 'third');

        $this->assertEquals('first', $result);
        $this->assertEquals('first', $this->cluster->get($alias));
    }

    public function testExpiry()
    {
        $alias = 'compare_and_swap expiry';
        $expiry = time() + 60;

        $this->cluster->put($alias, 'first');

        $this->cluster->compareAndSwap($alias, 'second', 'first', $expiry);

        $this->assertEquals($expiry, $this->cluster->getExpiryTime($alias));
    }
}

?>
