<?php

require_once 'QdbTestBase.php';

class QdbClusterUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->update('update not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->update('update too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->update(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->cluster->update('update wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->update('update wrong content', 'content', array());
    }

    public function testReturnValue()
    {
        $alias = 'update return';

        $result = $this->cluster->update($alias, 'content');

        $this->assertNull($result);
    }

    public function testSameAliasTwice()
    {
        $alias = 'update twice';

        $this->cluster->update($alias, 'first');
        $this->cluster->update($alias, 'second');

        $this->assertEquals('second', $this->cluster->get($alias));
    }

    public function testWithNoExpiry()
    {
        $alias = 'update no expiry';

        $this->cluster->update($alias, 'content');

        $this->assertEquals('content', $this->cluster->get($alias));
    }

    public function testWithExpiryInTheFuture()
    {
        $alias = 'update future';

        $this->cluster->update($alias, 'content', time() + 60);

        $this->assertEquals('content',  $this->cluster->get($alias));
    }

    /**
     * @expectedException               QdbInvalidArgumentException
     */
    public function testWithExpiryInThePast()
    {
        $alias = 'update past';

        $this->cluster->update($alias, 'some content', time() - 60);
    }
}

?>
