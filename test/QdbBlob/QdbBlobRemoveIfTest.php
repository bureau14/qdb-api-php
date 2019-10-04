<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbBlobRemoveIfTest extends QdbTestBase
{
    public function testNotEnoughArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/not enough/i');
        
        $blob = $this->createEmptyBlob();

        $blob->removeIf();
    }

    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $blob = $this->createEmptyBlob();

        $blob->removeIf('comparand', 'i should not be there');
    }

    public function testWrongComparandType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/comparand/i');
        
        $blob = $this->createEmptyBlob();

        $blob->removeIf(array());
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->removeIf('comparand');
    }

    public function DISABLED_testIncompatibleType()
    {
        $this->expectException('QdbIncompatibleTypeException');
        
        $alias = createUniqueAlias();
        $this->createInteger($alias);
        $blob = $this->createEmptyBlob($alias);

        $blob->removeIf('comparand');
    }

    public function testRemoveMatching()
    {
        $this->expectException('QdbAliasNotFoundException');
        
        $blob = $this->createEmptyBlob();

        $blob->put('comparand');
        $result = $blob->removeIf('comparand');

        $this->assertTrue($result);

        $blob->get();
    }

    public function testRemoveNotMatching()
    {
        $blob = $this->createEmptyBlob();

        $blob->put('first');
        $result = $blob->removeIf('second');

        $this->assertFalse($result);
        $this->assertEquals('first', $blob->get());
    }
}

?>
