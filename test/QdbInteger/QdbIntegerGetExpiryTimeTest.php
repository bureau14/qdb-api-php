<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbIntegerGetExpiryTimeTest extends QdbTestBase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $integer = $this->createEmptyInteger();

        $integer->getExpiryTime('i should not be there');
    }

    /**
     * @expectedException               QdbAliasNotFoundException
     * @expectedExceptionMessageRegExp  /found/i
     */
    public function testAliasNotFound()
    {
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
