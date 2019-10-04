<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBatchResultTest extends QdbTestBase
{
    protected $result;

    protected function setUp(): void
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

    public function testOutOfRange()
    {
        $this->expectException('OutOfRangeException');
        
        $this->result[-1];
    }

    public function testOutOfBounds()
    {
        $this->expectException('OutOfBoundsException');
        
        $this->result[0];
    }

    public function testSetForbidden()
    {
        $this->expectException('BadFunctionCallException');
        $this->expectExceptionMessageRegExp('/read-only/i');
        
        $this->result[0] = 1;
    }

    public function testUnsetForbidden()
    {
        $this->expectException('BadFunctionCallException');
        $this->expectExceptionMessageRegExp('/read-only/i');
        
        unset($this->result[0]);
    }
}

?>
