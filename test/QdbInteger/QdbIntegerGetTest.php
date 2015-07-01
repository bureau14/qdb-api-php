<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerGetTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->get('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $integer = $this->createEmptyInteger();

        $integer->get();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $integer = $this->createEmptyInteger($alias);

        $integer->get();
    }

    // NOTE: the result of get() is verified with put() and update() tests
}

?>
