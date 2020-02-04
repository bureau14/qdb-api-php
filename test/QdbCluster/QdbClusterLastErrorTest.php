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
        $err1 = new QdbLastError();
        
        try {
            $integer = $this->createEmptyInteger();
            $integer->put(1);
            $integer->put(2);
            $this->assertEquals('Should not reach this assert'. 0);
        } catch (Exception $e) {}
        $err2 = new QdbLastError();
        
        var_dump($err1->error());
        var_dump($err1->message());
        var_dump($err2->error());
        var_dump($err2->message());

        $this->assertEquals($err1->error(), 0xB1000008); // qdb_e_alias_not_found.
        $this->assertEquals($err1->message(), 'at qdb_remove: Could not find alias.');

        $this->assertEquals($err2->error(), 0xB1000009); // qdb_e_alias_already_exists.
        $this->assertEquals($err2->message(), 'at qdb_int_put: The alias already exists.');
    }
}

?>
