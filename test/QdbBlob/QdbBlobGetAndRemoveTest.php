<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetAndRemoveTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndRemove('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndRemove();
    }

    public function DISABLED_testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->getAndRemove();
    }

    public function testResult()
    {
        $blob = $this->createEmptyBlob();
        $content = createRandomContent();

        $blob->put($content);
        $result = $blob->getAndRemove();

        $this->assertEquals($content, $result);
    }

    public function testAliasRemoved()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createBlob();

        $blob->getAndRemove();
        $blob->getAndRemove();
    }
}

?>
