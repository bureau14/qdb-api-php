<?php

require_once 'QdbBlobTestBase.php';

class QdbBlobRemoveIfTest extends QdbBlobTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->blob->removeIf();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->blob->removeIf('comparand', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /comparand/i
     */
    public function testWrongComparandType()
    {
        $this->blob->removeIf(array());
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testAliasNotFound()
    {
        $this->blob->removeIf('comparand');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     */
    public function testRemoveMatching()
    {
        $this->blob->put('comparand');
        $result = $this->blob->removeIf('comparand');

        $this->assertTrue($result);

        $this->blob->get();
    }

    public function testRemoveNotMatching()
    {
        $this->blob->put('first');
        $result = $this->blob->removeIf('second');

        $this->assertFalse($result);
        $this->assertEquals('first', $this->blob->get());
    }
}

?>
