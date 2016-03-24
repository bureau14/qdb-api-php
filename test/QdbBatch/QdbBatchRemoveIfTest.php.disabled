<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchRemoveIfTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->removeIf('alias');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->removeIf('alias', 'comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->removeIf(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $batch = $this->createBatch();

        $batch->removeIf('alias', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->removeIf('alias', 'first');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->removeIf($blob->alias(), 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('first', $blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testSideEffect2()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->removeIf($blob->alias(), 'first');
        $result = $this->cluster->runBatch($batch);

        $blob->get(); // <- throws
    }

    public function testResultFalse()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->removeIf($blob->alias(), 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertFalse($result[0]);
    }

    public function testResultTrue()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->removeIf($blob->alias(), 'first');
        $result = $this->cluster->runBatch($batch);


        $this->assertTrue($result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $batch = $this->createBatch();

        $comparand = 'comparand';

        $batch->removeIf(createUniqueAlias(), $comparand);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
