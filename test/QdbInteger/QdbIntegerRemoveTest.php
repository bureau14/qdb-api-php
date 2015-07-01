<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->remove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
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

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveTwice()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(42);
        $integer->remove();

        $integer->remove();
    }
}

?>
