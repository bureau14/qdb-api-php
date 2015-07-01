<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchCompareAndSwapTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap(array(), 'content', 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', 'comparand', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->compareAndSwap('alias', 'content', 'comparand');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $blob->put($content1, $expiry1);

        $batch->compareAndSwap($blob->alias(), $content2, $content1, $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $blob->get());
        $this->assertEquals($expiry2, $blob->getExpiryTime());
    }

    public function testSideEffect2()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $blob->put($content1, $expiry1);

        $batch->compareAndSwap($blob->alias(), $content2, 'not matching', $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content1, $blob->get());
        $this->assertEquals($expiry1, $blob->getExpiryTime());
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->compareAndSwap($blob->alias(), 'second', 'first');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals('first', $result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $batch = $this->createBatch();

        $batch->compareAndSwap(createUniqueAlias(), 'content', 'comparand');
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
