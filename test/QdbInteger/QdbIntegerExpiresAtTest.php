<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerExpiresAtTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresAt();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresAt(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $integer = $this->createEmptyInteger();

        $integer->expiresAt("i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
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

    /**
     * @expectedException           QdbAliasNotFoundException
     */
    public function testExpiryInThePast()
    {
        $integer = $this->createEmptyInteger();
        $expiry = time() - 60;

        $integer->put(42);
        $integer->expiresAt($expiry);

        $integer->getExpiryTime();
    }
}

?>
