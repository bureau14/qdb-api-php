<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->remove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->remove('alias', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->remove(array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->remove('alias');

        $this->assertNull($result);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('content');

        $batch->remove($blob->alias());
        $result = $this->cluster->runBatch($batch);

        $blob->get(); // <- throws
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('content');

        $batch->remove($blob->alias());
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
        $batch = $this->createBatch();

        $batch->remove(createUniqueAlias());
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
