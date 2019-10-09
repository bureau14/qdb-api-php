<?php

use PHPUnit\Framework\TestCase;

class QdbBatchConstructTest extends TestCase
{
    public function testTooManyArguments()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessageRegExp('/too many/i');
        
        $batch = new QdbBatch('hello');
    }
}

?>
