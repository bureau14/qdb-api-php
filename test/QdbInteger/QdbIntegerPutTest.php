<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerPutTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->put();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42, 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /value/i
     */
    public function testWrongValueType()
    {
        $integer = $this->createEmptyInteger();

        $integer->put("i'm an integer... NOT!");
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42, "i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testSameAliasTwice()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(1);
        $integer->put(2);
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42, time() - 60);
        $integer->get();
    }
}

?>
