<?php

require_once 'QdbHashSetTestBase.php';

class QdbHashSetContainsTest extends QdbHashSetTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->hashSet->contains();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->hashSet->contains('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->hashSet->contains(array());
    }

    public function testReturnTrue()
    {
        $this->hashSet->insert('hello');
        $result = $this->hashSet->contains('hello');
        $this->assertEquals(true, $result);
    }

    public function testReturnFalse()
    {
        $this->hashSet->insert('hello');
        $result = $this->hashSet->contains('world');
        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testAfterPutBlob()
    {
        $this->blob->put('world');
        $this->hashSet->contains('hello');
    }
}

?>
