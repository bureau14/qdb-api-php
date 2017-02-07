<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobAttachTagsTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->attachTags();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array('tag'), 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /tag/i
     */
    public function testEmptyArray()
    {
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->attachTags(array('tag'));
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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
