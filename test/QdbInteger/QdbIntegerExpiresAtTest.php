<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerExpiresAtTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresAt();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresAt(0, 'i should not be there');
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresAt("i'm an expiry... NOT!");
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        $this->expectExceptionMessageRegExp('/found/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresAt(0);
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $result = $integer->expiresAt(time() + 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $integer = $this->createEmptyInteger();
        $expiry = time() + 60;

        $integer->put(42);
        $integer->expiresAt($expiry);

        $this->assertEquals($expiry, $integer->getExpiryTime());
    }

    public function testRemoveExpiry()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42, time() + 60);
        $integer->expiresAt(0);

        $this->assertEquals(0, $integer->getExpiryTime());
    }

    public function testExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();
        $expiry = time() - 60;

        $integer->put(42);
        $integer->expiresAt($expiry);

        $integer->getExpiryTime();
    }
}

?>
