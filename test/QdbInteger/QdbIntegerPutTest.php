<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerPutTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->integer->put();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->put(42, 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /value/i
     */
    public function testWrongValueType()
    {
        $this->integer->put("i'm an integer... NOT!");
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->integer->put(42, "i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testSameAliasTwice()
    {
        $this->integer->put(1);
        $this->integer->put(2);
    }

    public function testReturnValue()
    {
        $result = $this->integer->put(42);
        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $this->integer->put(42);
        $this->assertEquals(42, $this->integer->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $this->integer->put(42, time() + 60);
        $this->assertEquals(42, $this->integer->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $this->integer->put(42, time() - 60);
        $this->integer->get();
    }
}

?>
