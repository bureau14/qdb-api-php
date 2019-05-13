<?php

function createUniqueAlias()
{
    return md5(microtime()).md5(rand());
}

function createRandomContent()
{
    return md5(rand());
}

use PHPUnit\Framework\TestCase;

abstract class QdbTestBase extends TestCase
{
    protected $cluster;

    protected function setUp(): void
    {
        $this->cluster = new QdbCluster('qdb://127.0.0.1:20552/');
    }

    protected function createBlob($alias = NULL)
    {
        $blob = $this->createEmptyBlob($alias);
        $blob->put(createRandomContent());
        return $blob;
    }

    protected function createBatch()
    {
        return new QdbBatch();
    }

    protected function createEmptyBlob($alias = NULL)
    {
        $blob = $this->cluster->blob($alias ?: createUniqueAlias());
        return $blob;
    }

    protected function createEmptyInteger($alias = NULL)
    {
        $blob = $this->cluster->integer($alias ?: createUniqueAlias());
        return $blob;
    }

    protected function createEmptyTag($alias = NULL)
    {
        $tag = $this->cluster->tag($alias ?: createUniqueAlias());
        return $tag;
    }

    protected function createInteger($alias = NULL)
    {
        $integer = $this->createEmptyInteger($alias);
        $integer->put(42);
        return $integer;
    }

    protected function createQueue($alias = NULL)
    {
        $queue = $this->createEmptyQueue($alias);
        $queue->pushBack(createRandomContent());
        return $queue;
    }

    protected function createTag($alias = NULL)
    {
        $tag = $this->createEmptyTag($alias);
        $tag->attachEntry($this->createBlob()->alias());
        return $tag;
    }
}

?>
