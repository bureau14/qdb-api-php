<?php

require_once 'QdbTestBase.php';

class QdbClusterExpiresAtTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->expiresAt('expires_at not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->expiresAt('expires_at too many', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->expiresAt(array(), 0);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->expiresAt('expires_at', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->cluster->expiresAt('expires_at not found', 0);
    }

    public function testReturnValue()
    {
        $alias = 'expires_at return';

        $this->cluster->put($alias, 'content');
        $result = $this->cluster->expiresAt($alias, time() + 60);

        $this->assertNull($result);
    }

    public function testAddExpiry()
    {
        $alias = 'expires_at add';
        $expiry = time() + 60;

        $this->cluster->put($alias, 'content');
        $this->cluster->expiresAt($alias, $expiry);

        $this->assertEquals($expiry, $this->cluster->getExpiryTime($alias));
    }

    public function testRemoveExpiry()
    {
        $alias = 'expires_at remove';

        $this->cluster->put($alias, 'content', time() + 60);
        $this->cluster->expiresAt($alias, 0);

        $this->assertEquals(0, $this->cluster->getExpiryTime($alias));
    }
}

?>
