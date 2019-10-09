<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobExpiresFromNowTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow(0, 'i should not be there');
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow(0);
    }

    public function testReturnValue()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $result = $blob->expiresFromNow(60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $blob->expiresFromNow(60);

        $this->assertGreaterThan(time(), $blob->getExpiryTime());
    }

    public function testExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $blob->expiresFromNow(-60);
        $blob->getExpiryTime();
    }
}

?>
