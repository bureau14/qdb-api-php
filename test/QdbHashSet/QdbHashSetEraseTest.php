<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbHashSetEraseTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->erase();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->erase('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->erase(array());
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $hashSet = $this->createEmptyHashSet($alias);

        $hashSet->erase('hello');
    }

    public function testReturnFalse()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello');
        $result = $hashSet->erase('world');
        $this->assertEquals(false, $result);
    }

    public function testReturnTrue()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello');
        $result = $hashSet->erase('hello');
        $this->assertEquals(true, $result);
    }
}

?>
