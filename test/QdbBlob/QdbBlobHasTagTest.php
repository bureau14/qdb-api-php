<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobHasTagTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->hasTag();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->hasTag('tag', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /tag/i
     */
    public function testInvalidArgument()
    {
        $blob = $this->createEmptyBlob();

        $blob->hasTag(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->hasTag('tag');
    }

    public function testWithString()
    {
        $tag = createUniqueAlias();
        $blob = $this->createBlob();

        $this->assertFalse($blob->hasTag($tag));

        $blob->addTag($tag);

        $this->assertTrue($blob->hasTag($tag));
    }

    public function testWithQdbTag()
    {
        $tag = $this->createEmptyTag();
        $blob = $this->createBlob();

        $this->assertFalse($blob->hasTag($tag));

        $blob->addTag($tag);

        $this->assertTrue($blob->hasTag($tag));
    }
}

?>
