<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbHashSetContainsTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->contains();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->contains('hello', 'world');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /content/i
     */
    public function testWrongValueType()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->contains(array());
    }

    /**
     * @expectedException               QdbIncompatibleTypeException
     */
    public function testIncompatibleType()
    {
        $alias = createUniqueAlias();
        $blob = $this->createBlob($alias);
        $hashSet = $this->createEmptyHashSet($alias);

        $hashSet->contains('hello');
    }

    public function testReturnTrue()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello');
        $result = $hashSet->contains('hello');
        $this->assertEquals(true, $result);
    }

    public function testReturnFalse()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('hello');
        $result = $hashSet->contains('world');
        $this->assertEquals(false, $result);
    }
}

?>
