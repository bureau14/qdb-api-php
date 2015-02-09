<?php

require_once 'QdbTestBase.php';

class QdbClusterExpiresFromNowTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->expiresFromNow('expires_from_now not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->expiresFromNow('expires_from_now too many', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->expiresFromNow(array(), 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->expiresFromNow('expires_from_now', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  //i
     */
    public function testAliasNotFound()
    {
        $this->cluster->expiresFromNow('expires_from_now not found', 0);
    }

    public function testReturnValue()
    {
        $alias = 'expires_from_now return';

        $this->cluster->put($alias, 'content');
        $result = $this->cluster->expiresFromNow($alias, 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $alias = 'expires_from_now add';

        $this->cluster->put($alias, 'content');
        $this->cluster->expiresFromNow($alias, 60);

        $this->assertGreaterThan(time(), $this->cluster->getExpiryTime($alias));
    }
}

?>
