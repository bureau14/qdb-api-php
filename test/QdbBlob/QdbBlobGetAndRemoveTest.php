<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobGetAndRemoveTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->getAndRemove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->blob->getAndRemove();
    }

    public function testResult()
    {
        $content = 'content';

        $this->blob->put($content);
        $result = $this->blob->getAndRemove();

        $this->assertEquals($content, $result);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasRemoved()
    {
        $this->blob->put('content');
        $this->blob->getAndRemove();

        $this->blob->getAndRemove();
    }
}

?>
