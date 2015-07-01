<?php

require_once 'QdbBlobGetTest.php';

class QdbBlobGetAndUpdateTest extends QdbBlobGetTest
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate('content', array());
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->getAndUpdate('content');
    }

    public function testReplaceValue()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $blob->getAndUpdate('second');

        $this->assertEquals('second', $blob->get());
    }

    public function testReturnPreviousValue()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $result = $blob->getAndUpdate('second');

        $this->assertEquals('first', $result);
    }

    public function testNoExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first', time() + 60);
        $blob->getAndUpdate('second');

        $this->assertEquals(0, $blob->getExpiryTime());
    }

    public function testWithExpiry()
    {
        $blob = $this->createEmptyBlob();

        $expiry = time() + 60;

        $blob->put('first');
        $blob->getAndUpdate('second', $expiry);

        $this->assertEquals($expiry,  $blob->getExpiryTime());
    }
}

?>
