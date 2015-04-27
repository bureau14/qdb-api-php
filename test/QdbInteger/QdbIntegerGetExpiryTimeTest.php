<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerGetExpiryTimeTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->getExpiryTime('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->integer->getExpiryTime();
    }

    public function testAfterPut()
    {
        $expiry = time() + 60;

        $this->integer->put(42, $expiry);

        $this->assertEquals($expiry, $this->integer->getExpiryTime());
    }

    public function testAfterUpdate()
    {
        $expiry = time() + 60;

        $this->integer->update(42, $expiry);

        $this->assertEquals($expiry, $this->integer->getExpiryTime());
    }
}

?>
