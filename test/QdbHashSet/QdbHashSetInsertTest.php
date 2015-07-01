<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbHashSetInsertTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert(array());
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $hashSet = $this->createEmptyHashSet($alias);

        $hashSet->insert('hello');
    }

    public function testReturnTrue()
    {
        $hashSet = $this->createEmptyHashSet();

        $result = $hashSet->insert('hello');
        $this->assertEquals(true, $result);
    }

    public function testReturnFalse()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello');
        $result = $hashSet->insert('hello');
        $this->assertEquals(false, $result);
    }
}

?>
