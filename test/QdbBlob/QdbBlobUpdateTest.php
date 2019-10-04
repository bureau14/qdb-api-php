<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobUpdateTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->update();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->update('content', 0, 'i should not be there');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $blob = $this->createEmptyBlob();

        $blob->update(array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $blob = $this->createEmptyBlob();

        $blob->update('content', array());
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
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

    public function testWithExpiryInThePast()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->update('some content', time() - 60);
        $blob->get();
    }
}

?>
