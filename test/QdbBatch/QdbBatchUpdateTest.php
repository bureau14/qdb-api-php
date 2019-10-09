<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchUpdateTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $batch = $this->createBatch();

        $batch->update('alias');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = $this->createBatch();

        $batch->update('alias', 'content', 0, 'i should not be there');
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $batch = $this->createBatch();

        $batch->update(array(), 'content');
    }

    public function testWrongContentType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/content/i');
        
        $batch = $this->createBatch();

        $batch->update('alias', array());
    }

    public function testWrongExpiryType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/expiry/i');
        
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
