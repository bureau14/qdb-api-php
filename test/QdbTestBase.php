<?php

function createUniqueAlias()
{
    return md5(rand());
}

function createRandomContent()
{
    return md5(rand());
}

abstract class QdbTestBase extends PHPUnit_Framework_TestCase
{
    protected $cluster;

    protected function setUp()
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

    protected function createEmptyHashSet($alias = NULL)
    {
        $blob = $this->cluster->hashSet($alias ?: createUniqueAlias());
        return $blob;
    }

    protected function createEmptyInteger($alias = NULL)
    {
        $blob = $this->cluster->integer($alias ?: createUniqueAlias());
        return $blob;
    }

    protected function createEmptyQueue($alias = NULL)
    {
        $blob = $this->cluster->queue($alias ?: createUniqueAlias());
        return $blob;
    }

    protected function createEmptyTag($alias = NULL)
    {
        $tag = $this->cluster->tag($alias ?: createUniqueAlias());
        return $tag;
    }

    protected function createHashSet($alias = NULL)
    {
        $hashSet = $this->createEmptyHashSet($alias);
        $hashSet->insert(createRandomContent());
        return $hashSet;
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
        $tag->addEntry($this->createBlob()->alias());
        return $tag;
    }
}

?>
