<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->get('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->get();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function DISABLED_testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $this->createQueue($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->get();
    }


    // NOTE: the result of get() is verified with put() and update() tests
}

?>
