<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterRunBatchTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->runBatch();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->runBatch(new QdbBatch(), 0);
    }

    /**
     * @expectedException               PHPUnit_Framework_Error
     * @expectedExceptionMessageRegExp  /must be an instance of QdbBatch/
     */
    public function testWrongBatchType()
    {
        $this->cluster->runBatch(new stdClass);
    }

    public function testMultipleOperations()
    {
        $batch = new QdbBatch();
        $batch->compareAndSwap('batch cas', 'new cas value', 'old cas value');
        $batch->getAndRemove('batch get_remove');
        $batch->get('batch get');
        $batch->getAndUpdate('batch get_update', 'new get_update value');
        $batch->put('batch put', 'put value');
        $batch->removeIf('batch remove_if', 'remove_if value');
        $batch->remove('batch remove');
        $batch->update('batch update', 'new update value');

        $this->cluster->blob('batch cas')->put('old cas value');
        $this->cluster->blob('batch get_remove')->put('get_remove value');
        $this->cluster->blob('batch get')->put('get value');
        $this->cluster->blob('batch get_update')->put('old get_update value');
        $this->cluster->blob('batch remove_if')->put('remove_if value');
        $this->cluster->blob('batch remove')->put('remove value');
        $this->cluster->blob('batch update')->put('old update value');

        $result = $this->cluster->runBatch($batch);

        $this->assertNull($result[0]);
        $this->assertEquals('new cas value', $this->cluster->blob('batch cas')->get());
        $this->assertEquals('get_remove value', $result[1]);
        $this->assertEquals('get value', $result[2]);
        $this->assertEquals('old get_update value', $result[3]);
        $this->assertEquals('new get_update value', $this->cluster->blob('batch get_update')->get());
        $this->assertEquals('put value', $this->cluster->blob('batch put')->get());
        $this->assertNull($result[4]);
        $this->assertTrue($result[5]);
        $this->assertNull($result[6]);
        $this->assertEquals('new update value', $this->cluster->blob('batch update')->get());
        $this->assertNull($result[7]);
    }
}

?>
