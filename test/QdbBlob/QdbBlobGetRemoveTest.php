<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobGetRemoveTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->getRemove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->blob->getRemove();
    }

    public function testResult()
    {
        $content = 'content';

        $this->blob->put($content);
        $result = $this->blob->getRemove();

        $this->assertEquals($content, $result);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasRemoved()
    {
        $this->blob->put('content');
        $this->blob->getRemove();

        $this->blob->getRemove();
    }
}

?>
