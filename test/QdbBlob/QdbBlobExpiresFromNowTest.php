<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobExpiresFromNowTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->expiresFromNow(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
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

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testExpiryInThePast()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $blob->expiresFromNow(-60);
        $blob->getExpiryTime();
    }
}

?>
