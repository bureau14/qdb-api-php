QdbBatchTestBase<?php

require_once dirname(__FILE__).'/QdbBatchTestBase.php';

class QdbBatchUpdateTest extends QdbBatchTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->batch->update($this->alias);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->batch->update($this->alias, 'content', 0, 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /alias/i
     */
    public function testWrongAliasType()
    {
        $this->batch->update(array(), 'content');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongContentType()
    {
        $this->batch->update($this->alias, array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /expiry/i
     */
    public function testWrongExpiryType()
    {
        $this->batch->update($this->alias, 'content', array());
    }

    public function testReturnValue()
    {
        $result = $this->batch->update($this->alias, 'content');

        $this->assertNull($result);
    }

    public function testSideEffect()
    {
        $content1 = 'first';
        $content2 = 'second';
        $expiry1 = time() + 60;
        $expiry2 = time() + 120;

        $this->blob->put($content1, $expiry1);

        $this->batch->update($this->alias, $content2, $expiry2);
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals($content2, $this->blob->get());
        $this->assertEquals($expiry2, $this->blob->getExpiryTime());
    }

    public function testResult()
    {
        $this->batch->update($this->alias, 'content');
        $result = $this->cluster->runBatch($this->batch);

        $this->assertEquals(1, $result->count());
        $this->assertTrue(isset($result[0]));
        $this->assertNull($result[0]);
    }
}

?>
