<?php

require_once 'QdbHashSetTestBase.php';

class QdbHashSetEraseTest extends QdbHashSetTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->hashSet->erase();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->hashSet->erase('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->hashSet->erase(array());
    }

    public function testReturnFalse()
    {
        $this->hashSet->insert('hello');
        $result = $this->hashSet->erase('world');
        $this->assertEquals(false, $result);
    }

    public function testReturnTrue()
    {
        $this->hashSet->insert('hello');
        $result = $this->hashSet->erase('hello');
        $this->assertEquals(true, $result);
    }
}

?>
