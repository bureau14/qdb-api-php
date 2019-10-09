<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchPutTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $batch = $this->createBatch();

        $batch->put('alias');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = $this->createBatch();

        $batch->put('alias', 'content', 0, 'i should not be there');
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $batch = $this->createBatch();

        $batch->put(array(), 'content');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $batch = $this->createBatch();

        $batch->put('alias', array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $batch = $this->createBatch();

        $batch->put('alias', 'content', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->put('alias', 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'content';
        $expiry = time() + 60;

        $batch->put($blob->alias(), $content, $expiry);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content, $blob->get());
        $this->assertEquals($expiry, $blob->getExpiryTime());
    }

    public function testResult()
    {
        $batch = $this->createBatch();


        $batch->put('alias', 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    public function testException()
    {
        $this->expectException('QdbAliasAlreadyExistsException');
        
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'content';

        $blob->put($content);
        $batch->put($blob->alias(), $content);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
