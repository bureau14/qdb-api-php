<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterBlobTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $this->cluster->blob();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $this->cluster->blob('alias', 0);
    }

    public function testWrongAliasType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/alias/i');
        
        $this->cluster->blob(array());
    }

    public function testReturnType()
    {
        $blob = $this->cluster->blob(createUniqueAlias());
        $this->assertInstanceOf('QdbBlob', $blob);
        $this->assertInstanceOf('QdbExpirableEntry', $blob);
        $this->assertInstanceOf('QdbEntry', $blob);
    }

    public function testAlias()
    {
        $alias = createUniqueAlias();
        $blob = $this->cluster->blob($alias);
        $this->assertEquals($alias, $blob->alias());
    }
}

?>
