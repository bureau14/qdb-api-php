<?php

require_once 'QdbTestBase.php';

class QdbBatchCompareAndSwapTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch_cas not enough', 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch_cas too many', 'content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap(array(), 'content', 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch_cas wrong content', array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch_cas wrong content', 'content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch_cas wrong expiry', 'content', 'comparand', array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_cas return';

        $batch = new QdbBatch();
        $result = $batch->compareAndSwap($alias, 'content', 'comparand');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $alias = 'batch_cas side effect 1';
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->cluster->put($alias, $content1, $expiry1);

        $batch = new QdbBatch();
        $batch->compareAndSwap($alias, $content2, $content1, $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $this->cluster->get($alias));
        $this->assertEquals($expiry2, $this->cluster->getExpiryTime($alias));
    }

    public function testSideEffect2()
    {
        $alias = 'batch_cas side effect 2';
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->cluster->put($alias, $content1, $expiry1);

        $batch = new QdbBatch();
        $batch->compareAndSwap($alias, $content2, 'not matching', $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content1, $this->cluster->get($alias));
        $this->assertEquals($expiry1, $this->cluster->getExpiryTime($alias));
    }

    public function testResult()
    {
        $alias = 'batch_cas result';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->compareAndSwap($alias, 'second', 'first');
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
        $alias = 'batch_cas exception';

        $batch = new QdbBatch();
        $batch->compareAndSwap($alias, 'content', 'comparand');
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
