<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobPutTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->put();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->put('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->blob->put(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->blob->put('content', array());
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testSameAliasTwice()
    {
        $alias = 'put twice';

        $this->blob->put('first');
        $this->blob->put('second');
    }

    public function testReturnValue()
    {
        $result = $this->blob->put('content');

        $this->assertNull($result);
    }

    public function testWithNoExpiry()
    {
        $alias = 'put no expiry';

        $this->blob->put('content');

        $this->assertEquals('content', $this->blob->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $this->blob->put('content', time() + 60);

        $this->assertEquals('content', $this->blob->get());
    }

    /**
     * @expectedException               QdbInvalidArgumentException
     */
    public function testWithExpiryInThePast()
    {
        $this->blob->put('content', time() - 60);
    }
}

?>
