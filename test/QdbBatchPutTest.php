<?php

require_once 'QdbTestBase.php';

class QdbBatchPutTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->put('batch_put not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->put('batch_put too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->put(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = new QdbBatch();
        $batch->put('batch_put wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = new QdbBatch();
        $batch->put('batch_put wrong expiry', 'content', array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_put return';

        $batch = new QdbBatch();
        $result = $batch->put($alias, 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $alias = 'batch_put side effect';
        $content = 'content';
        $expiry = time() + 60;

        $batch = new QdbBatch();
        $batch->put($alias, $content, $expiry);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content, $this->cluster->get($alias));
        $this->assertEquals($expiry, $this->cluster->getExpiryTime($alias));
    }

    public function testResult()
    {
        $alias = 'batch_put result';

        $batch = new QdbBatch();
        $batch->put($alias, 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testException()
    {
        $alias = 'batch_put exception';
        $content = 'content';

        $batch = new QdbBatch();
        $this->cluster->put($alias, $content);
        $batch->put($alias, $content);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
