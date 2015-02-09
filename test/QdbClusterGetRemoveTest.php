<?php

require_once 'QdbTestBase.php';

class QdbClusterGetRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->getRemove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->getRemove('get_remove too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->cluster->getRemove(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->cluster->getRemove('get_remove not found');
    }

    public function testResult()
    {
        $alias = 'get_remove result';
        $content = 'content';

        $this->cluster->put($alias, $content);
        $result = $this->cluster->getRemove($alias);

        $this->assertEquals($content, $result);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasRemoved()
    {
        $alias = 'get_remove get';

        $this->cluster->put($alias, 'content');
        $this->cluster->getRemove($alias);

        $this->cluster->getRemove($alias);
    }
}

?>
