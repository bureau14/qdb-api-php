<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchCompareAndSwapTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', 'comparand', 0, 'i should not be there');
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap(array(), 'content', 'comparand');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', array(), 'comparand');
    }

    public function testWrongComparandType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/comparand/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $batch = $this->createBatch();

        $batch->compareAndSwap('alias', 'content', 'comparand', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->compareAndSwap('alias', 'content', 'comparand');

        $this->assertNull($result);
    }

    public function testMatching()
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

        // check result
        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);

        // check side effects
        $this->assertEquals($content2, $blob->get());
        $this->assertEquals($expiry2, $blob->getExpiryTime());
    }

    public function testNotMatching()
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

        // check result
        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals('first', $result[0]);

        // check side effects
        $this->assertEquals($content1, $blob->get());
        $this->assertEquals($expiry1, $blob->getExpiryTime());
    }

    public function testException()
    {
        $this->expectException('QdbAliasNotFoundException');

        $batch = $this->createBatch();

        $batch->compareAndSwap(createUniqueAlias(), 'content', 'comparand');
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
