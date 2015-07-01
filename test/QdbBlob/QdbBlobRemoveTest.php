<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->remove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->remove();
    }

    public function testReturnValue()
    {
        $blob = $this->createBlob();

        $result = $blob->remove();

        $this->assertNull($result);
    }

    public function testRemoveAndPut()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $blob->remove();
        $blob->put('second');

        $this->assertEquals('second', $blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveTwice()
    {
        $blob = $this->createBlob();

        $blob->remove();
        $blob->remove();
    }
}

?>
