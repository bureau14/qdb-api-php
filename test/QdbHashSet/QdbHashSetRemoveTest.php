<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbHashSetRemoveTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->remove('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->remove();
    }

    public function testInsertRemoveInsert()
    {
        $hashSet = $this->createEmptyHashSet();

        $hashSet->insert('first');
        $hashSet->remove();
        $hashSet->insert('second');

        $this->assertFalse($hashSet->contains('first'));
        $this->assertTrue($hashSet->contains('second'));
    }
}

?>
