<?php

require_once 'QdbBlobGetTest.php';

class QdbBlobGetAndUpdateTest extends QdbBlobGetTest
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate('content', 0, 'i should not be there');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate(array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->getAndUpdate('content', array());
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
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
