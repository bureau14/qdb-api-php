<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterRunBatchTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->runBatch();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->runBatch(new QdbBatch(), 0);
    }

    public function testWrongBatchType()
    {
        $this->expectException('TypeError');
        $this->expectExceptionMessageRegExp('/must be an instance of QdbBatch/');
        
        $this->cluster->runBatch(new stdClass);
    }

    public function testMultipleOperations()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch cas', 'new cas value', 'old cas value');
        // $batch->getAndRemove('batch get_remove');
        $batch->get('batch get');
        $batch->getAndUpdate('batch get_update', 'new get_update value');
        $batch->put('batch put', 'put value');
        // $batch->removeIf('batch remove_if', 'remove_if value');
        // $batch->remove('batch remove');
        $batch->update('batch update', 'new update value');

        $this->cluster->blob('batch cas')->put('old cas value');
        $this->cluster->blob('batch get_remove')->put('get_remove value');
        $this->cluster->blob('batch get')->put('get value');
        $this->cluster->blob('batch get_update')->put('old get_update value');
        $this->cluster->blob('batch remove_if')->put('remove_if value');
        $this->cluster->blob('batch remove')->put('remove value');
        $this->cluster->blob('batch update')->put('old update value');

        $result = $this->cluster->runBatch($batch);

        // compareAndSwap
        $this->assertNull($result[0]);
        $this->assertEquals('new cas value', $this->cluster->blob('batch cas')->get());

        // $this->assertEquals('get_remove value', $result[1]);

        // get
        $this->assertEquals('get value', $result[1]);

        // getAndUpdate
        $this->assertEquals('old get_update value', $result[2]);
        $this->assertEquals('new get_update value', $this->cluster->blob('batch get_update')->get());

        // put
        $this->assertEquals('put value', $this->cluster->blob('batch put')->get());
        $this->assertNull($result[3]);

        // $this->assertTrue($result[5]);
        // $this->assertNull($result[6]);

        // update
        $this->assertEquals('new update value', $this->cluster->blob('batch update')->get());
        $this->assertFalse($result[4]);
    }
}

?>
