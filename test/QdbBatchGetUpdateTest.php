<?php

require_once 'QdbTestBase.php';

class QdbBatchGetUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->getUpdate('batch_get_update not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->getUpdate('batch_get_update too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->getUpdate(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = new QdbBatch();
        $batch->getUpdate('batch_get_update wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = new QdbBatch();
        $batch->getUpdate('batch_get_update wrong expiry', 'content', array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_get_update return';

        $batch = new QdbBatch();
        $result = $batch->getUpdate($alias, 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $alias = 'batch_get_update side effect';
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->cluster->put($alias, $content1, $expiry1);

        $batch = new QdbBatch();
        $batch->getUpdate($alias, $content2, $expiry2);
        $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $this->cluster->get($alias));
        $this->assertEquals($expiry2, $this->cluster->getExpiryTime($alias));
    }

    public function testResult()
    {
        $alias = 'batch_get_update result';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->getUpdate($alias, 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('first', $result[0]);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testException()
    {
        $alias = 'batch_get_update exception';

        $batch = new QdbBatch();
        $batch->getUpdate($alias, 'content');

        $result = $this->cluster->runBatch($batch);
        
        $result[0]; // <- throws
    }
}

?>
