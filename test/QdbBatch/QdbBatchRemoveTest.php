<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchRemoveTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->remove();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->remove($this->getAlias(), 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->remove(array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->remove($this->getAlias());

        $this->assertNull($result);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testSideEffect()
    {
        $this->blob->put('content');

        $this->batch->remove($this->getAlias());
        $result = $this->cluster->runBatch($this->batch);

        $this->blob->get(); // <- throws
    }

    public function testResult()
    {
        $this->blob->put('content');

        $this->batch->remove($this->getAlias());
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testException()
    {
        $this->batch->remove($this->getAlias());
        $result = $this->cluster->runBatch($this->batch);

        $result[0]; // <- throws
    }
}

?>
