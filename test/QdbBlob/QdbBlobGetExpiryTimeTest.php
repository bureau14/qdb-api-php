<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobGetExpiryTimeTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->getExpiryTime('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->blob->getExpiryTime();
    }

    public function testAfterPut()
    {
        $expiry = time() + 60;

        $this->blob->put('content', $expiry);

        $this->assertEquals($expiry, $this->blob->getExpiryTime());
    }

    public function testAfterUpdate()
    {
        $expiry = time() + 60;

        $this->blob->update('content', $expiry);

        $this->assertEquals($expiry, $this->blob->getExpiryTime());
    }
}

?>
