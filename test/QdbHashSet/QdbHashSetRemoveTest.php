<?php

require_once 'QdbHashSetTestBase.php';

class QdbHashSetRemoveTest extends QdbHashSetTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->hashSet->remove('hello');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->hashSet->remove();
    }

    public function testInsertRemoveInsert()
    {
        $this->hashSet->insert('first');
        $this->hashSet->remove();
        $this->hashSet->insert('second');

        $this->assertFalse($this->hashSet->contains('first'));
        $this->assertTrue($this->hashSet->contains('second'));
    }
}

?>
