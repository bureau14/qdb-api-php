<?php

use PHPUnit\Framework\TestCase;

class PhpInfoTest extends TestCase
{
    private $info;

    protected function setUp(): void
    {
        ob_start();
        date_default_timezone_set("Europe/Paris");
        phpinfo(INFO_MODULES);
        $this->info = ob_get_contents();
        ob_end_clean();
    }

    public function testLogLevel()
    {
        $this->assertRegExp('/quasardb.log_level/', $this->info);
    }

    public function testPhpApiVersion()
    {
        $this->assertRegExp('/quasardb php extension/', $this->info);
    }

    public function testClientVersion()
    {
        $this->assertRegExp('/quasardb client version/', $this->info);
    }

    public function testClientBuild()
    {
        $this->assertRegExp('/quasardb client build/', $this->info);
    }
}

?>
