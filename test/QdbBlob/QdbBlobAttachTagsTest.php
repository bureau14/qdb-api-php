<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobAttachTagsTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTags();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array('tag'), 'i should not be there');
    }

    public function testEmptyArray()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/tag/i');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array('tag'));
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);

        $blob->attachTags(array($alias));
    }

    public function testWithString()
    {
        $blob = $this->createBlob();
        $tags = array(createUniqueAlias(), createUniqueAlias());

        $blob->attachTags($tags);

        $this->assertTrue($blob->hasTag($tags[0]));
        $this->assertTrue($blob->hasTag($tags[1]));
    }

    public function testWithQdbTag()
    {
        $blob = $this->createBlob();
        $tags = array($this->createEmptyTag(), $this->createEmptyTag());

        $blob->attachTags($tags);

        $this->assertTrue($blob->hasTag($tags[0]));
        $this->assertTrue($blob->hasTag($tags[1]));
    }

    public function testWithHash()
    {
        $blob = $this->createBlob();
        $tags = array(
            'hello' => createUniqueAlias(),
            'world' => createUniqueAlias()
        );

        $blob->attachTags($tags);

        $this->assertTrue($blob->hasTag($tags['hello']));
        $this->assertTrue($blob->hasTag($tags['world']));
    }
}

?>
