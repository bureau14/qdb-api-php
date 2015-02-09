<?php

require_once 'QdbTestBase.php';

class QdbBatchRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->remove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->remove('batch_remove too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->remove(array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_remove return';

        $batch = new QdbBatch();
        $result = $batch->remove($alias);

        $this->assertNull($result);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testSideEffect()
    {
        $alias = 'batch_remove side effect';

        $this->cluster->put($alias, 'content');

        $batch = new QdbBatch();
        $batch->remove($alias);
        $result = $this->cluster->runBatch($batch);

        $this->cluster->get($alias);
    }

    public function testResult()
    {
        $alias = 'batch_remove result';

        $this->cluster->put($alias, 'content');

        $batch = new QdbBatch();
        $batch->remove($alias);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $alias = 'batch_remove exception';

        $batch = new QdbBatch();
        $batch->remove($alias);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
