<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerUpdateTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->integer->update();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->update(42, 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /value/i
     */
    public function testWrongContentType()
    {
        $this->integer->update("i'm an integer... NOT!");
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->integer->update(42, "i'm an expiry... NOT!");
    }

    public function testReturnValue()
    {
        $result = $this->integer->update(42);
        $this->assertNull($result);
    }

    public function testSameAliasTwice()
    {
        $this->integer->update(1);
        $this->integer->update(2);

        $this->assertEquals(2, $this->integer->get());
    }

    public function testWithNoExpiry()
    {
        $this->integer->update(42);
        $this->assertEquals(42, $this->integer->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $this->integer->update(42, time() + 60);
        $this->assertEquals(42,  $this->integer->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $this->integer->update(42, time() - 60);
        $this->integer->get();
    }
}

?>
