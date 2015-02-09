<?php

require_once 'QdbTestBase.php';

class QdbClusterGetExpiryTimeTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->getExpiryTime();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->getExpiryTime('get_expiry_time too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->getExpiryTime(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $this->cluster->getExpiryTime('get_expiry_time not found');
    }

    public function testAfterPut()
    {
        $alias = 'get_expiry_time put';
        $expiry = time() + 60;

        $this->cluster->put($alias, 'content', $expiry);

        $this->assertEquals($expiry, $this->cluster->getExpiryTime($alias));
    }

    public function testAfterUpdate()
    {
        $alias = 'get_expiry_time update';
        $expiry = time() + 60;

        $this->cluster->update($alias, 'content', $expiry);

        $this->assertEquals($expiry, $this->cluster->getExpiryTime($alias));
    }
}

?>
