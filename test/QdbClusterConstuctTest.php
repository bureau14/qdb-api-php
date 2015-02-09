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
        $qdb = new QdbCluster(array(), 'i should not be there');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /nodes/i
     */
    public function testWrongArgumentype()
    {
        $qdb = new QdbCluster("127.0.0.1"); // this is tempting but doesn't work, you must use an array
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /empty/i
     */
    public function testEmptyNodeArray()
    {
        $nodes = array();
        $qdb = new QdbCluster($nodes);
    }

    public function testOneNode()
    {
        $nodes = array(
            array(
                'address' => '127.0.0.1',
                'port' => 20552
            )
        );
        $qdb = new QdbCluster($nodes);
        $this->assertNotNull($qdb);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /address/i
     */
    public function testAddressMissing()
    {
        $nodes = array(
            array(
                // 'address' => missing
                'port' => 20552
            )
        );
        $qdb = new QdbCluster($nodes);
    }

    /**
     * @expectedException               QdbClusterConnectionFailedException
     */
    public function testWrongAddress()
    {
        $nodes = array(
            array(
                'address' => '127.0.0.2',
                'port' => 20552
            )
        );
        $qdb = new QdbCluster($nodes);
    }

    /**
     * @expectedException               QdbClusterConnectionFailedException
     */
    public function testWrongPort()
    {
        $nodes = array(
            array(
                'address' => '127.0.0.1',
                'port' => 666
            )
        );
        $qdb = new QdbCluster($nodes);
    }
}

?>
