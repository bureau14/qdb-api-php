<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerExpiresAtTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->integer->expiresAt();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->expiresAt(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->integer->expiresAt("i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->integer->expiresAt(0);
    }

    public function testReturnValue()
    {
        $this->integer->put(42);
        $result = $this->integer->expiresAt(time() + 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $expiry = time() + 60;

        $this->integer->put(42);
        $this->integer->expiresAt($expiry);

        $this->assertEquals($expiry, $this->integer->getExpiryTime());
    }

    public function testRemoveExpiry()
    {
        $this->integer->put(42, time() + 60);
        $this->integer->expiresAt(0);

        $this->assertEquals(0, $this->integer->getExpiryTime());
    }

    /**
     * @expectedException           QdbInvalidArgumentException
     */
    public function testExpiryInThePast()
    {
        $expiry = time() - 60;

        $this->integer->put(42);
        $this->integer->expiresAt($expiry);
    }
}

?>
