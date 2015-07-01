<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->update();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42, 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /value/i
     */
    public function testWrongContentType()
    {
        $integer = $this->createEmptyInteger();

        $integer->update("i'm an integer... NOT!");
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42, "i'm an expiry... NOT!");
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $integer = $this->createEmptyInteger($alias);

        $integer->update(42);
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $result = $integer->update(42);
        $this->assertNull($result);
    }

    public function testSameAliasTwice()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(1);
        $integer->update(2);

        $this->assertEquals(2, $integer->get());
    }

    public function testWithNoExpiry()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42);
        $this->assertEquals(42, $integer->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42, time() + 60);
        $this->assertEquals(42,  $integer->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42, time() - 60);
        $integer->get();
    }
}

?>
