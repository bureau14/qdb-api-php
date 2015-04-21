<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchCompareAndSwapTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->compareAndSwap($this->getAlias(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->compareAndSwap($this->getAlias(), 'content', 'comparand', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->compareAndSwap(array(), 'content', 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->batch->compareAndSwap($this->getAlias(), array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->batch->compareAndSwap($this->getAlias(), 'content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->batch->compareAndSwap($this->getAlias(), 'content', 'comparand', array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->compareAndSwap($this->getAlias(), 'content', 'comparand');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->blob->put($content1, $expiry1);

        $this->batch->compareAndSwap($this->getAlias(), $content2, $content1, $expiry2);
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals($content2, $this->blob->get());
        $this->assertEquals($expiry2, $this->blob->getExpiryTime());
    }

    public function testSideEffect2()
    {
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->blob->put($content1, $expiry1);

        $this->batch->compareAndSwap($this->getAlias(), $content2, 'not matching', $expiry2);
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals($content1, $this->blob->get());
        $this->assertEquals($expiry1, $this->blob->getExpiryTime());
    }

    public function testResult()
    {
        $this->blob->put('first');

        $this->batch->compareAndSwap($this->getAlias(), 'second', 'first');
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals('first', $result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $this->batch->compareAndSwap($this->getAlias(), 'content', 'comparand');
        $result = $this->cluster->runBatch($this->batch);

        $result[0]; // <- throws
    }
}

?>
