
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQuerySignatureTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $query = $this->cluster->makeQuery();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $query = $this->cluster->makeQuery('CREATE', 'CHOCOLATE');
    }

    public function testIncompatibleType()
    {
        $this->expectException('InvalidArgumentException');
        
        $query = $this->cluster->makeQuery(42);
    }
}

?>
