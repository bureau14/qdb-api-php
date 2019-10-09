<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobHasTagTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->hasTag();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->hasTag('tag', 'i should not be there');
    }

    public function testInvalidArgument()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/tag/i');
        
        $blob = $this->createEmptyBlob();

        $blob->hasTag(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->hasTag('tag');
    }

    public function testWithString()
    {
        $tag = createUniqueAlias();
        $blob = $this->createBlob();

        $this->assertFalse($blob->hasTag($tag));

        $blob->attachTag($tag);

        $this->assertTrue($blob->hasTag($tag));
    }

    public function testWithQdbTag()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();

        $this->assertFalse($blob->hasTag($tag));

        $blob->attachTag($tag);

        $this->assertTrue($blob->hasTag($tag));
    }
}

?>
