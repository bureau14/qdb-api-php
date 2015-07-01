<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetAndRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndRemove('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndRemove();
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function DISABLED_testIncompatibleType()
    {
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

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasRemoved()
    {
        $blob = $this->createBlob();

        $blob->getAndRemove();
        $blob->getAndRemove();
    }
}

?>
