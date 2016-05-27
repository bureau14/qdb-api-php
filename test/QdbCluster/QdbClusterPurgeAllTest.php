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
     * @expectedException               QdbOperationDisabledException
     * @expectedExceptionMessageRegExp  /disabled/i
     */
    public function testOperationDisabled()
    {
        $this->cluster->purgeAll(120);
    }
}

?>
