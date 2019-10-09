<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobDetachTagTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->detachTag();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->detachTag('tag', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/tag/i');
        
        $blob = $this->createEmptyBlob();

        $blob->detachTag(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->detachTag('tag');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);

        $blob->detachTag($alias);
    }

    public function testWithString()
    {
        $tag = createUniqueAlias();
        $blob = $this->createBlob();
        $blob->attachTag($tag);

        $this->assertTrue($blob->detachTag($tag));
        $this->assertFalse($blob->detachTag($tag));
    }

    public function testWithQdbTag()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();
        $blob->attachTag($tag);

        $this->assertTrue($blob->detachTag($tag));
        $this->assertFalse($blob->detachTag($tag));
    }
}

?>
