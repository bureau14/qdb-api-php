<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterAuthentificationTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->setUserCredentials('');
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->setUserCredentials('', '', '');
    }

    public function testWrongType()
    {
        $this->expectException('InvalidArgumentException');
        
        $this->cluster->tag(array());
    }

    public function testBadCredentials()
    {
        $this->expectException('QdbException');
        
        $this->cluster->setUserCredentials('12', '34');
    }

    public function testBadPublicKey()
    {
        $this->expectException('QdbException');
        
        $this->cluster->setClusterPublicKey('123');
    }
}

?>
