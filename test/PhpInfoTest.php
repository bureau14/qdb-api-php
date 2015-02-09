<?php

class PhpInfoTest extends PHPUnit_Framework_TestCase
{
    private $info;

    protected function setUp()
    {
        ob_start();
        date_default_timezone_set("Europe/Paris");
        phpinfo(INFO_MODULES);
        $this->info = ob_get_contents();
        ob_end_clean();
    }

    public function testLogLevel()
    {
        $this->assertRegExp('/qdb.log_level/', $this->info);
    }

    public function testPhpApiVersion()
    {
        $this->assertRegExp('/qdb php api/', $this->info);
    }

    public function testClientVersion()
    {
        $this->assertRegExp('/qdb client version/', $this->info);
    }

    public function testClientBuild()
    {
        $this->assertRegExp('/qdb client build/', $this->info);
    }
}

?>
