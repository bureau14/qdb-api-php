<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerGetTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->get('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $integer = $this->createEmptyInteger();

        $integer->get();
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $integer = $this->createEmptyInteger($alias);

        $integer->get();
    }

    // NOTE: the result of get() is verified with put() and update() tests
}

?>
