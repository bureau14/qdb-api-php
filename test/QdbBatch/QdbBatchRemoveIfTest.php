<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchRemoveIfTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->removeIf($this->getAlias());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->removeIf($this->getAlias(), 'comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->removeIf(array(), 'comparand');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->batch->removeIf($this->getAlias(), array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->removeIf($this->getAlias(), 'first');

        $this->assertNull($result);
    }

    public function testSideEffect1()
    {
        $this->blob->put('first');

        $this->batch->removeIf($this->getAlias(), 'second');
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals('first', $this->blob->get());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testSideEffect2()
    {
        $this->blob->put('first');

        $this->batch->removeIf($this->getAlias(), 'first');
        $result = $this->cluster->runBatch($this->batch);

        $this->blob->get(); // <- throws
    }

    public function testResultFalse()
    {
        $this->blob->put('first');

        $this->batch->removeIf($this->getAlias(), 'second');
        $result = $this->cluster->runBatch($this->batch);

        $this->assertFalse($result[0]);
    }

    public function testResultTrue()
    {
        $this->blob->put('first');

        $this->batch->removeIf($this->getAlias(), 'first');
        $result = $this->cluster->runBatch($this->batch);


        $this->assertTrue($result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $comparand = 'comparand';

        $this->batch->removeIf($this->getAlias(), $comparand);
        $result = $this->cluster->runBatch($this->batch);

        $result[0]; // <- throws
    }
}

?>
