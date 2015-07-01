<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobRemoveTagTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeTag();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeTag('tag', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /tag/i
     */
    public function testInvalidArgument()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeTag(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->removeTag('tag');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);

        $blob->removeTag($alias);
    }

    public function testWithString()
    {
        $tag = createUniqueAlias();
        $blob = $this->createBlob();
        $blob->addTag($tag);

        $this->assertTrue($blob->removeTag($tag));
        $this->assertFalse($blob->removeTag($tag));
    }

    public function testWithQdbTag()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();
        $blob->addTag($tag);

        $this->assertTrue($blob->removeTag($tag));
        $this->assertFalse($blob->removeTag($tag));
    }
}

?>
