<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerAddTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $integer = $this->createEmptyInteger();

        $integer->add();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->add(42, 'i should not be there');
    }

    public function testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $blob = $this->createBlob();
        $integer = $this->createEmptyInteger($blob->alias());

        $integer->add(42);
    }

    public function testSideEffect()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(19);
        $integer->add(23);
        $this->assertEquals(42, $integer->get());
    }

    public function testReturnValue()
    {
        $integer = $this->createEmptyInteger();

        $integer->put(19);
        $result = $integer->add(23);
        $this->assertEquals(42, $result);
    }
}

?>
