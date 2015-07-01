<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerAddTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->add();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->add(42, 'i should not be there');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
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
