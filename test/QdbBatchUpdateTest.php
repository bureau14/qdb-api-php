<?php

require_once 'QdbTestBase.php';

class QdbBatchUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->update('batch_update not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->update('batch_update too many', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->update(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = new QdbBatch();
        $batch->update('batch_update wrong content', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = new QdbBatch();
        $batch->update('batch_update wrong expiry', 'content', array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_update return';

        $batch = new QdbBatch();
        $result = $batch->update($alias, 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $alias = 'batch_update side effect';
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->cluster->put($alias, $content1, $expiry1);

        $batch = new QdbBatch();
        $batch->update($alias, $content2, $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $this->cluster->get($alias));
        $this->assertEquals($expiry2, $this->cluster->getExpiryTime($alias));
    }

    public function testResult()
    {
        $alias = 'batch_update result';

        $batch = new QdbBatch();
        $batch->update($alias, 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }
}

?>
