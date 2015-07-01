<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchGetAndUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate('alias', 'content', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->getAndUpdate('alias', 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $blob->put($content1, $expiry1);

        $batch->getAndUpdate($blob->alias(), $content2, $expiry2);
        $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $blob->get());
        $this->assertEquals($expiry2, $blob->getExpiryTime());
    }

    public function testResult()
    {
        $batch = $this->createBatch();
        $blob = $this->createEmptyBlob();

        $blob->put('first');

        $batch->getAndUpdate($blob->alias(), 'second');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals('first', $result[0]);
    }

    /**
     * @expectedException       QdbAliasNotFoundException
     */
    public function testException()
    {
        $batch = $this->createBatch();

        $batch->getAndUpdate(createUniqueAlias(), 'content');

        $result = $this->cluster->runBatch($batch);

        $result[0]; // <- throws
    }
}

?>
