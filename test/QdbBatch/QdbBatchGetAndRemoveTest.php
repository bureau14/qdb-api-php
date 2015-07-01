<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchGetAndRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->getAndRemove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->getAndRemove('alias', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->getAndRemove(array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->getAndRemove('alias');

        $this->assertNull($result);
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'bazinga!';

        $blob->put($content);

        $batch->getAndRemove($blob->alias());
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
        $batch = $this->createBatch();

        $batch->getAndRemove(createUniqueAlias());

        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('content');

        $batch->getAndRemove($blob->alias());
        $result = $this->cluster->runBatch($batch);

        $blob->get(); // <- throws
    }
}

?>
