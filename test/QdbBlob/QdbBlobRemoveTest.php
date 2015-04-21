<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobRemoveTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->remove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->blob->remove();
    }

    public function testReturnValue()
    {
        $this->blob->put('content');
        $result = $this->blob->remove();

        $this->assertNull($result);
    }

    public function testRemoveAndPut()
    {
        $this->blob->put('first');
        $this->blob->remove();
        $this->blob->put('second');

        $this->assertEquals('second', $this->blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveTwice()
    {
        $this->blob->put('content');
        $this->blob->remove();

        $this->blob->remove();
    }
}

?>
