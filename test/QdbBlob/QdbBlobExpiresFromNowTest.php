<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobExpiresFromNowTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->expiresFromNow();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->expiresFromNow(0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->expiresFromNow(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  //i
     */
    public function testAliasNotFound()
    {
        $this->blob->expiresFromNow(0);
    }

    public function testReturnValue()
    {
        $this->blob->put('content');
        $result = $this->blob->expiresFromNow(60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $this->blob->put('content');
        $this->blob->expiresFromNow(60);

        $this->assertGreaterThan(time(), $this->blob->getExpiryTime());
    }
}

?>
