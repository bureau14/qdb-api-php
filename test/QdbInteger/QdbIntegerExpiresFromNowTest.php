<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerExpiresFromNowTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow(0, 'i should not be there');
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow("i'm an expiry... NOT!");
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow(0);
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $result = $integer->expiresFromNow(60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $integer->expiresFromNow(60);

        $this->assertGreaterThan(time(), $integer->getExpiryTime());
    }

    public function testExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $integer->expiresFromNow(-60);
        $integer->getExpiryTime();
    }
}

?>
