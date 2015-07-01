<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerExpiresFromNowTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresFromNow("i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
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

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testExpiryInThePast()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $integer->expiresFromNow(-60);
        $integer->getExpiryTime();
    }
}

?>
