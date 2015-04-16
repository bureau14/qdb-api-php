<?php

require_once 'QdbTestBase.php';

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
        $batch->getRemove('batch get_remove');
        $batch->get('batch get');
        $batch->getUpdate('batch get_update', 'new get_update value');
        $batch->put('batch put', 'put value');
        $batch->removeIf('batch remove_if', 'remove_if value');
        $batch->remove('batch remove');
        $batch->update('batch update', 'new update value');

        $this->cluster->put('batch cas', 'old cas value');
        $this->cluster->put('batch get_remove', 'get_remove value');
        $this->cluster->put('batch get', 'get value');
        $this->cluster->put('batch get_update', 'old get_update value');
        $this->cluster->put('batch remove_if', 'remove_if value');
        $this->cluster->put('batch remove', 'remove value');
        $this->cluster->put('batch update', 'old update value');

        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('old cas value', $result[0]);
        $this->assertEquals('new cas value', $this->cluster->get('batch cas'));
        $this->assertEquals('get_remove value', $result[1]);
        $this->assertEquals('get value', $result[2]);
        $this->assertEquals('old get_update value', $result[3]);
        $this->assertEquals('new get_update value', $this->cluster->get('batch get_update'));
        $this->assertEquals('put value', $this->cluster->get('batch put'));
        $this->assertNull($result[4]);
        $this->assertTrue($result[5]);
        $this->assertNull($result[6]);
        $this->assertEquals('new update value', $this->cluster->get('batch update'));
        $this->assertNull($result[7]);
    }
}

?>
