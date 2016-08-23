<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->update();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $blob = $this->createEmptyBlob();

        $blob->update(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('content', array());
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->update('content');
    }

    public function testReturnTrueWhenCalledOnce()
    {
        $blob = $this->createEmptyBlob();

        $result = $blob->update('content');

        $this->assertTrue($result);
    }

    public function testReturnFalseWhenCalledTwice()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('content');
        $result = $blob->update('content');

        $this->assertFalse($result);
    }

    public function testSameAliasTwice()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('first');
        $blob->update('second');

        $this->assertEquals('second', $blob->get());
    }

    public function testWithNoExpiry()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('content');

        $this->assertEquals('content', $blob->get());
    }

    public function testWithExpiryInTheFuture()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('content', time() + 60);

        $this->assertEquals('content',  $blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testWithExpiryInThePast()
    {
        $blob = $this->createEmptyBlob();

        $blob->update('some content', time() - 60);
        $blob->get();
    }
}

?>
