<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobUpdateTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->update();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->update('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->blob->update(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->update('content', array());
    }

    public function testReturnValue()
    {
        $result = $this->blob->update('content');

        $this->assertNull($result);
    }

    public function testSameAliasTwice()
    {
        $this->blob->update('first');
        $this->blob->update('second');

        $this->assertEquals('second', $this->blob->get());
    }

    public function testWithNoExpiry()
    {
        $this->blob->update('content');

        $this->assertEquals('content', $this->blob->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $this->blob->update('content', time() + 60);

        $this->assertEquals('content',  $this->blob->get());
    }

    /**
     * @expectedException               QdbInvalidArgumentException
     */
    public function testWithExpiryInThePast()
    {
        $this->blob->update('some content', time() - 60);
    }
}

?>
