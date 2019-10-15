
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffectsTest extends QdbTestBase
{
    public function testFillTable()
    {
        try {
            $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
            $this->assertEquals(count($query->columnNames()), 0);
            $this->assertEquals(count($query->rows()), 0);
            $this->assertEquals($query->scannedPointCount(), 0);

            $query = $this->cluster->makeQuery('INSERT INTO persons($timestamp, name, age)'.
                                            'VALUES (1970, "Alice", 21), (1970-01-01T00:00:01, "Bob", 22)');
            $this->assertEquals(count($query->columnNames()), 0);
            $this->assertEquals(count($query->rows()), 0);
            $this->assertEquals($query->scannedPointCount(), 0);
            
            $query = $this->cluster->makeQuery('SELECT * FROM persons');
            $this->assertEquals(count($query->columnNames()), 4);
            $this->assertEquals(count($query->rows()), 2);
            $this->assertEquals($query->scannedPointCount(), 4);

            $this->assertEquals($query->columnNames(), ['$timestamp', '$table', 'name', 'age']);

            $this->assertEquals($query->pointsRows()[0][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->pointsRows()[1][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->pointsRows()[0][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->pointsRows()[1][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->pointsRows()[0][2]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->pointsRows()[1][2]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($query->pointsRows()[0][3]->type(), QdbQueryPoint::INT64);
            $this->assertEquals($query->pointsRows()[1][3]->type(), QdbQueryPoint::INT64);

            $this->assertEquals($query->pointsRows()[0][0]->value(), new QdbTimestamp(0, 0));
            $this->assertEquals($query->pointsRows()[1][0]->value(), new QdbTimestamp(1, 0));
            $this->assertEquals($query->pointsRows()[0][1]->value(), 'persons');
            $this->assertEquals($query->pointsRows()[1][1]->value(), 'persons');
            $this->assertEquals($query->pointsRows()[0][2]->value(), 'Alice');
            $this->assertEquals($query->pointsRows()[1][2]->value(), 'Bob');
            $this->assertEquals($query->pointsRows()[0][3]->value(), 21);
            $this->assertEquals($query->pointsRows()[1][3]->value(), 22);
        }
        finally {
            $query = $this->cluster->makeQuery('DROP TABLE persons');
        }
    }
}

?>
