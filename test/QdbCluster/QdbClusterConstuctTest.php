<?php

class QdbClusterConstructorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $qdb = new QdbCluster();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $qdb = new QdbCluster('qdb://127.0.0.1:20552/', 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /must be a string/
     */
    public function testWrongArgumentype()
    {
        $qdb = new QdbCluster(array());
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /URI/
     */
    public function testInvalidUri()
    {
        $qdb = new QdbCluster('http://www.quasardb.net/');
    }

    public function testOneNode()
    {
        $qdb = new QdbCluster('qdb://127.0.0.1:20552/');
        $this->assertNotNull($qdb);
    }
}

?>
