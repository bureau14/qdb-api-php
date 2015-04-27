<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerRemoveTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->remove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->integer->remove();
    }

    public function testReturnValue()
    {
        $this->integer->put(42);
        $result = $this->integer->remove();

        $this->assertNull($result);
    }

    public function testRemoveAndPut()
    {
        $this->integer->put(1);
        $this->integer->remove();
        $this->integer->put(2);

        $this->assertEquals(2, $this->integer->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveTwice()
    {
        $this->integer->put(42);
        $this->integer->remove();

        $this->integer->remove();
    }
}

?>
