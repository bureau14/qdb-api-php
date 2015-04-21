<?php

require_once 'QdbBlobGetTest.php';

class QdbBlobGetUpdateTest extends QdbBlobGetTest
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->getUpdate();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->getUpdate('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->blob->getUpdate(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->getUpdate('content', array());
    }

    public function testReplaceValue()
    {
        $this->blob->put('first');
        $this->blob->getUpdate('second');

        $this->assertEquals('second', $this->blob->get());
    }

    public function testReturnPreviousValue()
    {
        $this->blob->put('first');
        $result = $this->blob->getUpdate('second');

        $this->assertEquals('first', $result);
    }

    public function testNoExpiry()
    {
        $this->blob->put('first', time() + 60);
        $this->blob->getUpdate('second');

        $this->assertEquals(0, $this->blob->getExpiryTime());
    }

    public function testWithExpiry()
    {
        $expiry = time() + 60;

        $this->blob->put('first');
        $this->blob->getUpdate('second', $expiry);

        $this->assertEquals($expiry,  $this->blob->getExpiryTime());
    }
}

?>
