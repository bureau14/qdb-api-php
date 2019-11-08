
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffectsTest extends QdbTestBase
{
    private function checkEmptyQuery($str)
    {
        $query = $this->cluster->makeQuery($str);
        $this->assertEquals(count($query->columnNames()), 0);
        $this->assertEquals(count($query->rows()), 0);
        $this->assertEquals($query->scannedPointCount(), 0);
    }

    public function testFillTable()
    {
        try {
            $this->checkEmptyQuery('CREATE TABLE persons(name BLOB, age INT64)');
            $this->checkEmptyQuery('INSERT INTO  persons($timestamp, name, age) VALUES'.
                                   '  (1970-01-01T00:00:00, "Alice", 21)'.
                                   ', (1970-01-01T00:00:01, "Bob",   22)');
            
            $query = $this->cluster->makeQuery('SELECT * FROM persons');
            $this->assertEquals(count($query->columnNames()), 4);
            $this->assertEquals(count($query->rows()), 2);
            $this->assertEquals($query->scannedPointCount(), 4);

            $this->assertEquals($query->columnNames(), ['$timestamp', '$table', 'name', 'age']);

            $this->assertEquals($query->rows()[0][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->rows()[1][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->rows()[0][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[1][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[0][2]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[1][2]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[0][3]->type(), QdbQueryPoint::INT64);
            $this->assertEquals($query->rows()[1][3]->type(), QdbQueryPoint::INT64);

            $this->assertEquals($query->rows()[0][0]->value(), new QdbTimestamp(0, 0));
            $this->assertEquals($query->rows()[1][0]->value(), new QdbTimestamp(1, 0));
            $this->assertEquals($query->rows()[0][1]->value(), 'persons');
            $this->assertEquals($query->rows()[1][1]->value(), 'persons');
            $this->assertEquals($query->rows()[0][2]->value(), 'Alice');
            $this->assertEquals($query->rows()[1][2]->value(), 'Bob');
            $this->assertEquals($query->rows()[0][3]->value(), 21);
            $this->assertEquals($query->rows()[1][3]->value(), 22);
        }
        finally {
            $this->checkEmptyQuery('DROP TABLE persons');
        }
    }
    
    public function testNullValues()
    {
        try {
            $this->checkEmptyQuery('CREATE TABLE flavours(blob BLOB, int64 INT64, ts TIMESTAMP, f64 DOUBLE)');
            $this->checkEmptyQuery('INSERT INTO  flavours($timestamp, blob, int64, ts, f64) VALUES'.
                                   '  (now, "Alice", null, null, null)'.
                                   ', (now, "Bob",   22,   1970, null)'.
                                   ', (now, null,    23,   null, null)');

            $query = $this->cluster->makeQuery('SELECT count(int64), blob, int64, ts, f64 FROM flavours');

            $this->assertEquals($query->columnNames(), ['count(int64)', 'blob', 'int64', 'ts', 'f64']);

            $this->assertEquals($query->rows()[0][0]->type(), QdbQueryPoint::COUNT);
            $this->assertEquals($query->rows()[1][0]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[2][0]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[0][1]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[1][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[2][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->rows()[0][2]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[1][2]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[2][2]->type(), QdbQueryPoint::INT64);
            $this->assertEquals($query->rows()[0][3]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[1][3]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[2][3]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[0][4]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[1][4]->type(), QdbQueryPoint::NONE);
            $this->assertEquals($query->rows()[2][4]->type(), QdbQueryPoint::NONE);

            $this->assertEquals($query->rows()[0][0]->value(), 2);
            $this->assertEquals($query->rows()[1][0]->value(), null);
            $this->assertEquals($query->rows()[2][0]->value(), null);
            $this->assertEquals($query->rows()[0][1]->value(), 'Alice');
            $this->assertEquals($query->rows()[1][1]->value(), 'Bob');
            $this->assertEquals($query->rows()[2][1]->value(), null);
            $this->assertEquals($query->rows()[0][2]->value(), null);
            $this->assertEquals($query->rows()[1][2]->value(), 22);
            $this->assertEquals($query->rows()[2][2]->value(), 23);
            $this->assertEquals($query->rows()[0][3]->value(), null);
            $this->assertEquals($query->rows()[1][3]->value(), new QdbTimestamp(0, 0));
            $this->assertEquals($query->rows()[2][3]->value(), null);
            $this->assertEquals($query->rows()[0][4]->value(), null);
            $this->assertEquals($query->rows()[1][4]->value(), null);
            $this->assertEquals($query->rows()[2][4]->value(), null);
        }
        finally {
            $this->checkEmptyQuery('DROP TABLE flavours');
        }
    }

}

?>
