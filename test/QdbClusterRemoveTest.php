<?php

require_once 'QdbTestBase.php';

class QdbClusterRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->remove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->remove('remove too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->remove(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->cluster->remove('remove not found');
    }

    public function testReturnValue()
    {
        $alias = 'remove return';

        $this->cluster->put($alias, 'content');
        $result = $this->cluster->remove($alias);

        $this->assertNull($result);
    }

    public function testRemoveAndPut()
    {
        $alias = 'remove put';

        $this->cluster->put($alias, 'first');
        $this->cluster->remove($alias);
        $this->cluster->put($alias, 'second');

        $this->assertEquals('second', $this->cluster->get($alias));
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveTwice()
    {
        $alias = 'remove twice';

        $this->cluster->put($alias, 'content');
        $this->cluster->remove($alias);

        $this->cluster->remove($alias);
    }
}

?>
