<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchGetAndUpdateTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', 'content', 0, 'i should not be there');
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate(array(), 'content');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', 'content', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->getAndUpdate('alias', 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $blob->put($content1, $expiry1);

        $batch->getAndUpdate($blob->alias(), $content2, $expiry2);
        $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $blob->get());
        $this->assertEquals($expiry2, $blob->getExpiryTime());
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->getAndUpdate($blob->alias(), 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('first', $result[0]);
    }

    public function testException()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $batch = $this->createBatch();

        $batch->getAndUpdate(createUniqueAlias(), 'content');

        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
