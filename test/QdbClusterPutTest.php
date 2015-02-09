<?php

require_once 'QdbTestBase.php';

class QdbClusterPutTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->put('put not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->put('put too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->put(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->cluster->put('put wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->put('put wrong content', 'content', array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testSameAliasTwice()
    {
        $alias = 'put twice';

        $this->cluster->put($alias, 'first');
        $this->cluster->put($alias, 'second');
    }

    public function testReturnValue()
    {
        $alias = 'put return';

        $result = $this->cluster->put($alias, 'content');

        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $alias = 'put no expiry';

        $this->cluster->put($alias, 'content');

        $this->assertEquals('content', $this->cluster->get($alias));
    }

    public function testWithExpiryInTheFuture()
    {
        $alias = 'put future';

        $this->cluster->put($alias, 'content', time() + 60);

        $this->assertEquals('content', $this->cluster->get($alias));
    }

    /**
     * @expectedException               QdbInvalidArgumentException
     */
    public function testWithExpiryInThePast()
    {
        $alias = 'put past';

        $this->cluster->put($alias, 'content', time() - 60);
    }
}

?>
