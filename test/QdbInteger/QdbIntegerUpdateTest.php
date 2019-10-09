<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerUpdateTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $integer = $this->createEmptyInteger();

        $integer->update();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->update(42, 0, 'i should not be there');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/value/i');
        
        $integer = $this->createEmptyInteger();

        $integer->update("i'm an integer... NOT!");
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $integer = $this->createEmptyInteger();

        $integer->update(42, "i'm an expiry... NOT!");
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $integer = $this->createEmptyInteger($alias);

        $integer->update(42);
    }

    public function testReturnTrueWhenCalledOnce()
    {
        $integer = $this->createEmptyInteger();

        $result = $integer->update(42);

        $this->assertTrue($result);
    }

    public function testReturnFalseWhenCalledTwice()
    {
        $integer = $this->createEmptyInteger();

        $integer->update(42);
        $result = $integer->update(42);

        $this->assertFalse($result);
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

    public function testWithExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->update(42, time() - 60);
        $integer->get();
    }
}

?>
