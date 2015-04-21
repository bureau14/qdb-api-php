<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobCompareAndSwapTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->compareAndSwap('content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->compareAndSwap('content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->blob->compareAndSwap(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->blob->compareAndSwap('content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->compareAndSwap('content', 'comparand', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->blob->compareAndSwap('content', 'comparand');
    }

    public function testMatching()
    {
        $this->blob->put('first');

        $result = $this->blob->compareAndSwap('second', 'first');

        $this->assertEquals('first', $result);
        $this->assertEquals('second', $this->blob->get());
    }

    public function testRemoveNotMatching()
    {
        $this->blob->put('first');

        $result = $this->blob->compareAndSwap('second', 'third');

        $this->assertEquals('first', $result);
        $this->assertEquals('first', $this->blob->get());
    }

    public function testExpiry()
    {
        $expiry = time() + 60;

        $this->blob->put('first');
        $this->blob->compareAndSwap('second', 'first', $expiry);

        $this->assertEquals($expiry, $this->blob->getExpiryTime());
    }
}

?>
