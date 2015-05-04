<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerExpiresFromNowTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->integer->expiresFromNow();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->expiresFromNow(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->integer->expiresFromNow("i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->integer->expiresFromNow(0);
    }

    public function testReturnValue()
    {
        $this->integer->put(42);
        $result = $this->integer->expiresFromNow(60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $this->integer->put(42);
        $this->integer->expiresFromNow(60);

        $this->assertGreaterThan(time(), $this->integer->getExpiryTime());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testExpiryInThePast()
    {
        $this->integer->put(42);
        $this->integer->expiresFromNow(-60);
        $this->integer->getExpiryTime();
    }
}

?>
