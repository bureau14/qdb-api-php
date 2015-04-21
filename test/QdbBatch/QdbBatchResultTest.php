<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchResultTest extends QdbTestBase
{
    protected $result;

    protected function setUp()
    {
        parent::setUp();

        $this->result = $this->cluster->runBatch(new QdbBatch());
    }

    public function testClassType()
    {
        $this->assertInstanceOf('QdbBatchResult', $this->result);
    }

    public function testCountableInterface()
    {
        $this->assertInstanceOf('Countable', $this->result);
    }

    public function testArrayAccessInterface()
    {
        $this->assertInstanceOf('ArrayAccess', $this->result);
    }

    public function testCountFunction()
    {
        $this->assertEquals(0, $this->result->count());
    }

    public function testIsSetFalse()
    {
        $this->assertFalse(isset($this->result[0]));
    }

    /**
     * @expectedException   OutOfRangeException
     */
    public function testOutOfRange()
    {
        $this->result[-1];
    }

    /**
     * @expectedException   OutOfBoundsException
     */
    public function testOutOfBounds()
    {
        $this->result[0];
    }

    /**
     * @expectedException               BadFunctionCallException
     * @expectedExceptionMessageRegExp  /read-only/i
     */
    public function testSetForbidden()
    {
        $this->result[0] = 1;
    }

    /**
     * @expectedException               BadFunctionCallException
     * @expectedExceptionMessageRegExp  /read-only/i
     */
    public function testUnsetForbidden()
    {
        unset($this->result[0]);
    }
}

?>
