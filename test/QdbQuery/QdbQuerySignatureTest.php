
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQuerySignatureTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /not enough/i
     */
    public function testNotEnoughArguments()
    {
        $query = $this->cluster->makeQuery();
    }

    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $query = $this->cluster->makeQuery('CREATE', 'CHOCOLATE');
    }

    /**
     * @expectedException               InvalidArgumentException
     */
    public function testIncompatibleType()
    {
        $query = $this->cluster->makeQuery(42);
    }
}

?>
