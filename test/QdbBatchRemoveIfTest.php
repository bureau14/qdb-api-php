<?php

require_once 'QdbTestBase.php';

class QdbBatchRemoveIfTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = new QdbBatch();
        $batch->removeIf('batch_remove_if not enough');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch();
        $batch->removeIf('batch_remove_if too many', 'comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = new QdbBatch();
        $batch->removeIf(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $batch = new QdbBatch();
        $batch->removeIf('batch_remove_if wrong comparand', array());
    }

    public function testReturnValue()
    {
        $alias = 'batch_remove_if return';

        $batch = new QdbBatch();
        $result = $batch->removeIf($alias, 'first');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $alias = 'batch_remove_if side effect 1';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->removeIf($alias, 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('first', $this->cluster->get($alias));
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testSideEffect2()
    {
        $alias = 'batch_remove_if side effect 2';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->removeIf($alias, 'first');
        $result = $this->cluster->runBatch($batch);

        $this->cluster->get($alias); // <- throws
    }

    public function testResultFalse()
    {
        $alias = 'batch_remove_if false';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->removeIf($alias, 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertFalse($result[0]);
    }

    public function testResultTrue()
    {
        $alias = 'batch_remove_if true';

        $this->cluster->put($alias, 'first');

        $batch = new QdbBatch();
        $batch->removeIf($alias, 'first');
        $result = $this->cluster->runBatch($batch);


        $this->assertTrue($result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $alias = 'batch_remove_if exception';
        $comparand = 'comparand';

        $batch = new QdbBatch();
        $batch->removeIf($alias, $comparand);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
