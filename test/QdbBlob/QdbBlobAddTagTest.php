<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobAddTagTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->addTag();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->addTag('tag', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /tag/i
     */
    public function testInvalidArgument()
    {
        $blob = $this->createEmptyBlob();

        $blob->addTag(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->addTag('tag');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $tag = $this->createEmptyTag($alias);

        $blob->addTag($alias);
    }

    public function testWithString()
    {
        $blob = $this->createBlob();
        $tag = createUniqueAlias();

        $this->assertTrue($blob->addTag($tag));
        $this->assertFalse($blob->addTag($tag));
    }

    public function testWithQdbTag()
    {
        $blob = $this->createBlob();
        $tag = $this->createEmptyTag();

        $this->assertTrue($blob->addTag($tag));
        $this->assertFalse($blob->addTag($tag));
    }
}

?>
