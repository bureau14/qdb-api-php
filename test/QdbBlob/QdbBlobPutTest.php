<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobPutTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->put();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $blob = $this->createEmptyBlob();

        $blob->put(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testSameAliasTwice()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $blob->put('second');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->put('content');
    }

    public function testReturnValue()
    {
        $blob = $this->createEmptyBlob();

        $result = $blob->put('content');
        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content');
        $this->assertEquals('content', $blob->get());
    }

    public function testWithExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', time() + 60);
        $this->assertEquals('content', $blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('content', time() - 60);
        $blob->get();
    }
}

?>
