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
        $this->blob->getAndUpdate();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->getAndUpdate('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->blob->getAndUpdate(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->getAndUpdate('content', array());
    }

    public function testReplaceValue()
    {
        $this->blob->put('first');
        $this->blob->getAndUpdate('second');

        $this->assertEquals('second', $this->blob->get());
    }

    public function testReturnPreviousValue()
    {
        $this->blob->put('first');
        $result = $this->blob->getAndUpdate('second');

        $this->assertEquals('first', $result);
    }

    public function testNoExpiry()
    {
        $this->blob->put('first', time() + 60);
        $this->blob->getAndUpdate('second');

        $this->assertEquals(0, $this->blob->getExpiryTime());
    }

    public function testWithExpiry()
    {
        $expiry = time() + 60;

        $this->blob->put('first');
        $this->blob->getAndUpdate('second', $expiry);

        $this->assertEquals($expiry,  $this->blob->getExpiryTime());
    }
}

?>
