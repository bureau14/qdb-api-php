<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobExpiresAtTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->expiresAt();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->expiresAt(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->expiresAt(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->blob->expiresAt(0);
    }

    public function testReturnValue()
    {
        $this->blob->put('content');
        $result = $this->blob->expiresAt(time() + 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $expiry = time() + 60;

        $this->blob->put('content');
        $this->blob->expiresAt($expiry);

        $this->assertEquals($expiry, $this->blob->getExpiryTime());
    }

    public function testRemoveExpiry()
    {
        $this->blob->put('content', time() + 60);
        $this->blob->expiresAt(0);

        $this->assertEquals(0, $this->blob->getExpiryTime());
    }
}

?>
