<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobRemoveTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->remove('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
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

    public function testRemoveTwice()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createBlob();

        $blob->remove();
        $blob->remove();
    }
}

?>
