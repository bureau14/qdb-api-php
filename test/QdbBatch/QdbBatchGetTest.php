<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchGetTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->get();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->get($this->alias, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->get(array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->get($this->alias);

        $this->assertNull($result);
    }

    public function testResult()
    {
        $content = 'bazinga!';

        $this->blob->put($content);

        $this->batch->get($this->alias);
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertEquals($content, $result[0]);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testException()
    {
        $this->batch->get($this->alias);

        $result = $this->cluster->runBatch($this->batch);

        $result[0]; // <- throws
    }
}

?>
