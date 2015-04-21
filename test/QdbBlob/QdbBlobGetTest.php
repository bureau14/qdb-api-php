<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobGetTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->get('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->blob->get();
    }

    // NOTE: the result of get() is verified with put() and update() tests
}

?>
