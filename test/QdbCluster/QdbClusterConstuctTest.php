<?php

use PHPUnit\Framework\TestCase;

class QdbClusterConstructorTest extends TestCase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $qdb = new QdbCluster();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $qdb = new QdbCluster('qdb://127.0.0.1:20552/', 'i should not be there');
    }

    public function testWrongArgumentype()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/must be a string/');
        
        $qdb = new QdbCluster(array());
    }

    public function testInvalidUri()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/URI/');
        
        $qdb = new QdbCluster('http://www.quasardb.net/');
    }

    public function testOneNode()
    {
        $qdb = new QdbCluster('qdb://127.0.0.1:20552/');
        $this->assertNotNull($qdb);
    }
}

?>
