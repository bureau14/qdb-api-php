<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerPutTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $integer = $this->createEmptyInteger();

        $integer->put();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->put(42, 0, 'i should not be there');
    }

    public function testWrongValueType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/value/i');
        
        $integer = $this->createEmptyInteger();

        $integer->put("i'm an integer... NOT!");
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $integer = $this->createEmptyInteger();

        $integer->put(42, "i'm an expiry... NOT!");
    }

    public function testSameAliasTwice()
    {
        $this->expectException('QdbAliasAlreadyExistsException');
        
        $integer = $this->createEmptyInteger();

        $integer->put(1);
        $integer->put(2);
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $integer = $this->createEmptyInteger($alias);

        $integer->put(42);
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $result = $integer->put(42);
        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $this->assertEquals(42, $integer->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42, time() + 60);
        $this->assertEquals(42, $integer->get());
    }

    public function testWithExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->put(42, time() - 60);
        $integer->get();
    }
}

?>
