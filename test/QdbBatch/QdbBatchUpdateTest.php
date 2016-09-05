QdbTestBase<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchUpdateTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $batch = $this->createBatch();

        $batch->update('alias');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = $this->createBatch();

        $batch->update('alias', 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $batch = $this->createBatch();

        $batch->update(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $batch = $this->createBatch();

        $batch->update('alias', array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $batch = $this->createBatch();

        $batch->update('alias', 'content', array());
    }

    public function testReturnValue()
    {
        $batch = $this->createBatch();

        $result = $batch->update('alias', 'content');

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

        $batch->update($blob->alias(), $content2, $expiry2);
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals($content2, $blob->get());
        $this->assertEquals($expiry2, $blob->getExpiryTime());
    }

    public function testReturnTrueWhenCalledOnce()
    {
        $batch = $this->createBatch();

        $batch->update(createUniqueAlias(), 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertTrue($result[0]);
    }

    public function testReturnFalseWhenCalledTwice()
    {
        $alias = createUniqueAlias();
        $blob = $this->createEmptyBlob($alias);
        $blob->update('content');
        $batch = $this->createBatch();

        $batch->update($alias, 'content');
        $result = $this->cluster->runBatch($batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertFalse($result[0]);
    }
}

?>
