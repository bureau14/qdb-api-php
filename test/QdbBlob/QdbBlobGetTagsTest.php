<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobGetTagsTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $blob = $this->createEmptyBlob();

        $blob->getTags('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
        $blob = $this->createEmptyBlob();

        $blob->getTags();
    }

    public function testEmpty()
    {
        $blob = $this->createBlob();

        $result = $blob->getTags();

        $this->assertCount(0, $result);
    }

    public function testResult()
    {
        $blob = $this->createBlob();

        $blob->attachTag('tag1');
        $blob->attachTag('tag2');

        $result = iterator_to_array($blob->getTags());
        $this->assertCount(2, $result);

        $this->assertInstanceOf('QdbTag', $result[0]);
        $this->assertInstanceOf('QdbTag', $result[1]);

        $aliases = array_map(function($tag){return $tag->alias();}, $result);
        $this->assertContains('tag1', $aliases);
        $this->assertContains('tag2', $aliases);
    }

    public function testResultType()
    {
        $blob = $this->createBlob();

        $result = $blob->getTags();

        $this->assertInstanceOf('QdbTagCollection', $result);
    }
}

?>
