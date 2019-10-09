<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobExpiresAtTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresAt();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresAt(0, 'i should not be there');
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresAt(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        $this->expectExceptionMessageRegExp('/found/i');
        
        $blob = $this->createEmptyBlob();

        $blob->expiresAt(0);
    }

    public function testReturnValue()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $result = $blob->expiresAt(time() + 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $blob = $this->createEmptyBlob();

        $expiry = time() + 60;

        $blob->put('content');
        $blob->expiresAt($expiry);

        $this->assertEquals($expiry, $blob->getExpiryTime());
    }

    public function testRemoveExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', time() + 60);
        $blob->expiresAt(0);

        $this->assertEquals(0, $blob->getExpiryTime());
    }

    public function testExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $expiry = time() - 60;

        $blob->put('content');
        $blob->expiresAt($expiry);
        $blob->getExpiryTime();
    }
}

?>
