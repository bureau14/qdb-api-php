<?php

require_once 'QdbHashSetTestBase.php';

class QdbHashSetInsertTest extends QdbHashSetTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->hashSet->insert();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->hashSet->insert('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $this->hashSet->insert(array());
    }

    public function testReturnTrue()
    {
        $result = $this->hashSet->insert('hello');
        $this->assertEquals(true, $result);
    }

    public function testReturnFalse()
    {
        $this->hashSet->insert('hello');
        $result = $this->hashSet->insert('hello');
        $this->assertEquals(false, $result);
    }

    /**
     * @expectedException               QdbAliasAlreadyExistsException
     */
    public function testBeforePutBlob()
    {
        $this->hashSet->insert('hello');
        $this->blob->put('world');
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testAfterPutBlob()
    {
        $this->blob->put('world');
        $this->hashSet->insert('hello');
    }
}

?>
