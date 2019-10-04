<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterPurgeAllTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->purgeAll(80, "i should not be there");
    }

    public function testNegativeTimeout()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/timeout/i');
        
        $this->cluster->purgeAll(-120);
    }

    public function testNullTimeout()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/timeout/i');
        
        $this->cluster->purgeAll(0);
    }

    public function testInvalidType()
    {
        $this->expectException('InvalidArgumentException');
        
        $this->cluster->purgeAll('120 seconds');
    }

    public function testPositiveTimeout()
    {
        $this->expectException('QdbOperationDisabledException');
        $this->expectExceptionMessageRegExp('/disabled/i');
        
        $this->cluster->purgeAll(120);
    }

    public function testDefaultTimeout()
    {
        $this->expectException('QdbOperationDisabledException');
        $this->expectExceptionMessageRegExp('/disabled/i');
        
        $this->cluster->purgeAll();
    }
}

?>
