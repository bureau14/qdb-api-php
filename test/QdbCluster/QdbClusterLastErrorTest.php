<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterLastErrorTest extends QdbTestBase
{
    public function testErrors()
    {
        try {
            $this->createEmptyBlob()->remove();
            $this->assertEquals('Should not reach this assert'. 0);
        } catch (Exception $e) {}
        $msg1 = $this->cluster->lastError();

        try {
            $integer = $this->createEmptyInteger();
            $integer->put(1);
            $integer->put(2);
            $this->assertEquals('Should not reach this assert'. 0);
        } catch (Exception $e) {}
        $msg2 = $this->cluster->lastError();

        $this->assertEquals($msg1, 'at qdb_remove: An entry matching the provided alias cannot be found.');
        $this->assertEquals($msg2, 'at qdb_int_put: An entry matching the provided alias already exists.');
    }
}

?>
