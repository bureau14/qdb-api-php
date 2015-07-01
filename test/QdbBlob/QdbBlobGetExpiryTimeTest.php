<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetExpiryTimeTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->getExpiryTime('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->getExpiryTime();
    }

    public function testAfterPut()
    {
        $blob = $this->createEmptyBlob();
        $expiry = time() + 60;

        $blob->put('content', $expiry);

        $this->assertEquals($expiry, $blob->getExpiryTime());
    }

    public function testAfterUpdate()
    {
        $blob = $this->createEmptyBlob();
        $expiry = time() + 60;

        $blob->update('content', $expiry);

        $this->assertEquals($expiry, $blob->getExpiryTime());
    }
}

?>
