<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobPutTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->put();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->put('content', 0, 'i should not be there');
    }

    public function testWrongValueType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $blob = $this->createEmptyBlob();

        $blob->put(array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->put('content', array());
    }

    public function testSameAliasTwice()
    {
        $this->expectException('QdbAliasAlreadyExistsException');
        
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $blob->put('second');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->put('content');
    }

    public function testReturnValue()
    {
        $blob = $this->createEmptyBlob();

        $result = $blob->put('content');
        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $this->assertEquals('content', $blob->get());
    }

    public function testWithExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', time() + 60);
        $this->assertEquals('content', $blob->get());
    }

    public function testWithExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->put('content', time() - 60);
        $blob->get();
    }
}

?>
