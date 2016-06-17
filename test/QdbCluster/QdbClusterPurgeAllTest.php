<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterPurgeAllTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $this->cluster->purgeAll(80, "i should not be there");
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /timeout/i
     */
    public function testNegativeTimeout()
    {
        $this->cluster->purgeAll(-120);
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /timeout/i
     */
    public function testNullTimeout()
    {
        $this->cluster->purgeAll(0);
    }

    /**
     * @expectedException               InvalidArgumentException
     */
    public function testInvalidType()
    {
        $this->cluster->purgeAll('120 seconds');
    }

    /**
     * @expectedException               QdbOperationDisabledException
     * @expectedExceptionMessageRegExp  /disabled/i
     */
    public function testPositiveTimeout()
    {
        $this->cluster->purgeAll(120);
    }

    /**
     * @expectedException               QdbOperationDisabledException
     * @expectedExceptionMessageRegExp  /disabled/i
     */
    public function testDefaultTimeout()
    {
        $this->cluster->purgeAll();
    }
}

?>
