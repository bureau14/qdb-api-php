QdbTestBase<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchPutTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->put('alias');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->put('alias', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->put(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = $this->createBatch();

        $batch->put('alias', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = $this->createBatch();

        $batch->put('alias', 'content', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->put('alias', 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'content';
        $expiry = time() + 60;

        $batch->put($blob->alias(), $content, $expiry);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content, $blob->get());
        $this->assertEquals($expiry, $blob->getExpiryTime());
    }

    public function testResult()
    {
        $batch = $this->createBatch();


        $batch->put('alias', 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testException()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content = 'content';

        $blob->put($content);
        $batch->put($blob->alias(), $content);
        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
