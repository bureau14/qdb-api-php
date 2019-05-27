<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterAuthentificationTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $this->cluster->setUserCredentials('');
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->setUserCredentials('', '', '');
    }

    /**
     * @expectedException  InvalidArgumentException
     */
    public function testWrongType()
    {
        $this->cluster->tag(array());
    }

    /**
     * @expectedException  QdbException
     */
    public function testBadCredentials()
    {
        $this->cluster->setUserCredentials('12', '34');
    }

    /**
     * @expectedException  QdbException
     */
    public function testBadPublicKey()
    {
        $this->cluster->setClusterPublicKey('123');
    }
}

?>
