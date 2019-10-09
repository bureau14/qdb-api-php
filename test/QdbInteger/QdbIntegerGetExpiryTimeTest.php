<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerGetExpiryTimeTest extends QdbTestBase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $integer = $this->createEmptyInteger();

        $integer->getExpiryTime('i should not be there');
    }

    public function testAliasNotFound()
    {
        $this->expectException('QdbAliasNotFoundException');
        $this->expectExceptionMessageRegExp('/found/i');
        
        $integer = $this->createEmptyInteger();

        $integer->getExpiryTime();
    }

    public function testAfterPut()
    {
        $integer = $this->createEmptyInteger();

        $expiry = time() + 60;

        $integer->put(42, $expiry);

        $this->assertEquals($expiry, $integer->getExpiryTime());
    }

    public function testAfterUpdate()
    {
        $integer = $this->createEmptyInteger();

        $expiry = time() + 60;

        $integer->update(42, $expiry);

        $this->assertEquals($expiry, $integer->getExpiryTime());
    }
}

?>
