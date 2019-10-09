<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobAttachTagTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTag();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTag('tag', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/tag/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTag(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTag('tag');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $tag = $this->createEmptyTag($alias);

        $blob->attachTag($alias);
    }

    public function testWithString()
    {
        $blob = $this->createBlob();
        $tag = createUniqueAlias();

        $this->assertTrue($blob->attachTag($tag));
        $this->assertFalse($blob->attachTag($tag));
    }

    public function testWithQdbTag()
    {
        $blob = $this->createBlob();
        $tag = $this->createEmptyTag();

        $this->assertTrue($blob->attachTag($tag));
        $this->assertFalse($blob->attachTag($tag));
    }
}

?>
