<?php

require_once 'QdbTestBase.php';

class QdbClusterGetUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->getUpdate('get_update not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->getUpdate('get_update too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->getUpdate(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->cluster->getUpdate('get_update wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->cluster->getUpdate('get_update wrong expiry', 'content', array());
    }

    public function testReplaceValue()
    {
        $alias = 'get_update replace';

        $this->cluster->put($alias, 'first');
        $this->cluster->getUpdate($alias, 'second');

        $this->assertEquals('second', $this->cluster->get($alias));
    }

    public function testReturnPreviousValue()
    {
        $alias = 'get_update return';

        $this->cluster->put($alias, 'first');
        $result = $this->cluster->getUpdate($alias, 'second');

        $this->assertEquals('first', $result);
    }

    public function testNoExpiry()
    {
        $alias = 'get_update no expiry';

        $this->cluster->put($alias, 'first', time() + 60);
        $this->cluster->getUpdate($alias, 'second');

        $this->assertEquals(0, $this->cluster->getExpiryTime($alias));
    }

    public function testWithExpiry()
    {
        $alias = 'get_update expiry';
        $expiry = time() + 60;

        $this->cluster->put($alias, 'first');
        $this->cluster->getUpdate($alias, 'second', $expiry);

        $this->assertEquals($expiry,  $this->cluster->getExpiryTime($alias));
    }
}

?>
