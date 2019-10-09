<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetExpiryTimeTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getExpiryTime('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        $this->expectExceptionMessageRegExp('/found/i');
        
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
