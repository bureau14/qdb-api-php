<?php

require_once 'QdbTestBase.php';

class QdbBatchGetTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->get();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->get('batch_get too many', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->get(array());
    }

    public function testReturnValue()
    {
        $batch = new QdbBatch();
        $result = $batch->get('batch_get return');

        $this->assertNull($result);
    }

    public function testResult()
    {
        $alias = 'batch_get result';
        $content = 'bazinga!';

        $this->cluster->put($alias, $content);

        $batch = new QdbBatch();
        $batch->get($alias);
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
        $alias = 'batch_get exception';

        $batch = new QdbBatch();
        $batch->get($alias);

        $result = $this->cluster->runBatch($batch);
        
        $result[0]; // <- throws
    }
}

?>
