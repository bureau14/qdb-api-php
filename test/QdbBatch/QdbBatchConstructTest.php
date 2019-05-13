<?php

use PHPUnit\Framework\TestCase;

class QdbBatchConstructTest extends TestCase
{
    /**
     * @expectedException               InvalidArgumentException
     * @expectedExceptionMessageRegExp  /too many/i
     */
    public function testTooManyArguments()
    {
        $batch = new QdbBatch('hello');
    }
}

?>
