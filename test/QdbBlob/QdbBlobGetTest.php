<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->get('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->get();
    }

    public function DISABLED_testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $this->createQueue($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->get();
    }


    // NOTE: the result of get() is verified with put() and update() tests
}

?>
