<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobdetachTagTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->detachTag();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->detachTag('tag', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /tag/i
     */
    public function testInvalidArgument()
    {
        $blob = $this->createEmptyBlob();

        $blob->detachTag(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->detachTag('tag');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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
