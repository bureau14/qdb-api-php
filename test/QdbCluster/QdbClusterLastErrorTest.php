<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbClusterLastErrorTest extends QdbTestBase
{
    public function testRemoveError()
    {
        try {
            $this->createEmptyBlob()->remove();
            $this->assertEquals('Should not reach this assert'. 0);
        } catch (Exception $e) {}

        $err = new QdbLastError();
        $this->assertEquals($err->error(), 2969567240); // Code for qdb_e_alias_not_found.
        $this->assertEquals($err->message(), 'at qdb_remove: Could not find alias.');
    }
}

?>
