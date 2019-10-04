<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobCompareAndSwapTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand', 0, 'i should not be there');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap(array(), 'comparand');
    }

    public function testWrongComparandType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/comparand/i');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand', array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->compareAndSwap('content', 'comparand');
    }

    public function DISABLED_testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
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

        $this->assertNull($result);
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
