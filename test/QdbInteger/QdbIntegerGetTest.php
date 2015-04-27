<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerGetTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->get('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->integer->get();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testOnABlob()
    {
        $this->blob->put("i'm not an integer");
        $this->integer->get();
    }

    // NOTE: the result of get() is verified with put() and update() tests
}

?>
