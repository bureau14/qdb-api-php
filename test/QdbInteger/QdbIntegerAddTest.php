<?php

require_once 'QdbIntegerTestBase.php';

class QdbIntegerAddTest extends QdbIntegerTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->integer->add();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->integer->add(42, 'i should not be there');
    }

    public function testSideEffect()
    {
        $this->integer->put(19);
        $this->integer->add(23);
        $this->assertEquals(42, $this->integer->get());
    }

    public function testReturnValue()
    {
        $this->integer->put(19);
        $result = $this->integer->add(23);
        $this->assertEquals(42, $result);
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testOnABlob()
    {
        $this->blob->put("i'm not an integer :-P");
        $this->integer->add(42);
    }
}

?>
