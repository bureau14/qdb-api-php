QdbBatchTestBase<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchPutTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->put($this->getAlias());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->put($this->getAlias(), 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->put(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->batch->put($this->getAlias(), array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->batch->put($this->getAlias(), 'content', array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->put($this->getAlias(), 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $content = 'content';
        $expiry = time() + 60;

        $this->batch->put($this->getAlias(), $content, $expiry);
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals($content, $this->blob->get());
        $this->assertEquals($expiry, $this->blob->getExpiryTime());
    }

    public function testResult()
    {

        $this->batch->put($this->getAlias(), 'content');
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testException()
    {
        $content = 'content';

        $this->blob->put($content);
        $this->batch->put($this->getAlias(), $content);
        $result = $this->cluster->runBatch($this->batch);

        $result[0]; // <- throws
    }
}

?>
