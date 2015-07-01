<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobExpiresAtTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresAt();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresAt(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresAt(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
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

    /**
     * @expectedException           QdbAliasNotFoundException
     */
    public function testExpiryInThePast()
    {
        $blob = $this->createEmptyBlob();

        $expiry = time() - 60;

        $blob->put('content');
        $blob->expiresAt($expiry);
        $blob->getExpiryTime();
    }
}

?>
