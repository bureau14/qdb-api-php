<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobRemoveIfTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeIf();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeIf('comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeIf(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeIf('comparand');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function DISABLED_testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->removeIf('comparand');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveMatching()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('comparand');
        $result = $blob->removeIf('comparand');

        $this->assertTrue($result);

        $blob->get();
    }

    public function testRemoveNotMatching()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $result = $blob->removeIf('second');

        $this->assertFalse($result);
        $this->assertEquals('first', $blob->get());
    }
}

?>
