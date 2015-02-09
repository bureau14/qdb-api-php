<?php

require_once 'QdbTestBase.php';

class QdbBatchGetRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->getRemove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->getRemove('batch_get_remove too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->getRemove(array());
    }

    public function testReturnValue()
    {
        $batch = new QdbBatch();
        $result = $batch->getRemove('batch_get_remove return');

        $this->assertNull($result);
    }

    public function testResult()
    {
        $alias = 'batch_get_remove result';
        $content = 'bazinga!';

        $this->cluster->put($alias, $content);

        $batch = new QdbBatch();
        $batch->getRemove($alias);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals($content, $result[0]);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testException()
    {
        $alias = 'batch_get_remove exception';

        $batch = new QdbBatch();
        $batch->getRemove($alias);

        $result = $this->cluster->runBatch($batch);
        
        $result[0]; // <- throws
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testSideEffect()
    {
        $alias = 'batch_remove side effect';

        $this->cluster->put($alias, 'content');

        $batch = new QdbBatch();
        $batch->getRemove($alias);
        $result = $this->cluster->runBatch($batch);

        $this->cluster->get($alias); // <- throws
    }
}

?>
