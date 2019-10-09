<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerRemoveTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->remove('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->remove();
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $result = $integer->remove();

        $this->assertNull($result);
    }

    public function testRemoveAndPut()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(1);
        $integer->remove();
        $integer->put(2);

        $this->assertEquals(2, $integer->get());
    }

    public function testRemoveTwice()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $integer->remove();

        $integer->remove();
    }
}

?>
