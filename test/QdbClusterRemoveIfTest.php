<?php

require_once 'QdbTestBase.php';

class QdbClusterRemoveIfTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->removeIf('remove_if not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->removeIf('remove_if too many', 'comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->removeIf(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->cluster->removeIf('remove_if wrong comparand', array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->cluster->removeIf('remove_if not found', 'comparand');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveMatching()
    {
        $alias = 'remove_if matching';

        $this->cluster->put($alias, 'comparand');
        $result = $this->cluster->removeIf($alias, 'comparand');

        $this->assertTrue($result);

        $this->cluster->get($alias);
    }

    public function testRemoveNotMatching()
    {
        $alias = 'remove_if not matching';

        $this->cluster->put($alias, 'first');
        $result = $this->cluster->removeIf($alias, 'second');

        $this->assertFalse($result);
        $this->assertEquals('first', $this->cluster->get($alias));
    }
}

?>
