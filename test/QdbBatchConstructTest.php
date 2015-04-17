<?php

class QdbBatchConstructTest extends PHPUnit_Framework_TestCase
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
