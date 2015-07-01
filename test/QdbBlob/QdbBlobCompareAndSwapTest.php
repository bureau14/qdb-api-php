<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobCompareAndSwapTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function DISABLED_testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $int = $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->compareAndSwap('content', 'comparand');
    }

    public function testMatching()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $result = $blob->compareAndSwap('second', 'first');

        $this->assertEquals('first', $result);
        $this->assertEquals('second', $blob->get());
    }

    public function testRemoveNotMatching()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $result = $blob->compareAndSwap('second', 'third');

        $this->assertEquals('first', $result);
        $this->assertEquals('first', $blob->get());
    }

    public function testExpiry()
    {
        $blob = $this->createEmptyBlob();
        $expiry = time() + 60;

        $blob->put('first');
        $blob->compareAndSwap('second', 'first', $expiry);

        $this->assertEquals($expiry, $blob->getExpiryTime());
    }
}

?>
