<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchGetTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $batch = $this->createBatch();

        $batch->get();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = $this->createBatch();

        $batch->get('alias', 'i should not be there');
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $batch = $this->createBatch();

        $batch->get(array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->get('alias');

        $this->assertNull($result);
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'bazinga!';

        $blob->put($content);

        $batch->get($blob->alias());
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals($content, $result[0]);
    }

    public function testException()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $batch = $this->createBatch();

        $batch->get(createUniqueAlias());

        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
