
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffectsTest extends QdbTestBase
{
    public function testFillTable()
    {
        try {
            $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
            $this->assertEquals(count($query->tables()), 0);
            $this->assertEquals($query->scannedPointCount(), 0);

            $query = $this->cluster->makeQuery('INSERT INTO persons($timestamp, name, age)'.
                                            'VALUES (1970, "Alice", 21), (1970-01-01T00:00:01, "Bob", 22)');
            $this->assertEquals(count($query->tables()), 0);
            $this->assertEquals($query->scannedPointCount(), 0);
            
            $query = $this->cluster->makeQuery('SELECT * FROM persons');
            $this->assertEquals(count($query->tables()), 1);
            $this->assertEquals($query->scannedPointCount(), 4);

            $table = $query->tables()[0];
            $this->assertEquals($table->tableName(),    'persons');
            $this->assertEquals($table->columnsNames(), ['timestamp', 'name', 'age']);
            $this->assertEquals($table->rowsCount(),    2);
            $this->assertEquals($table->pointsRows()[0][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($table->pointsRows()[1][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($table->pointsRows()[0][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($table->pointsRows()[1][1]->type(), QdbQueryPoint::BLOB);
            $this->assertEquals($table->pointsRows()[0][2]->type(), QdbQueryPoint::INT64);
            $this->assertEquals($table->pointsRows()[1][2]->type(), QdbQueryPoint::INT64);
            $this->assertEquals($table->pointsRows()[0][0]->value(), new QdbTimestamp(0, 0));
            $this->assertEquals($table->pointsRows()[1][0]->value(), new QdbTimestamp(1, 0));
            $this->assertEquals($table->pointsRows()[0][1]->value(), 'Alice');
            $this->assertEquals($table->pointsRows()[1][1]->value(), 'Bob');
            $this->assertEquals($table->pointsRows()[0][2]->value(), 21);
            $this->assertEquals($table->pointsRows()[1][2]->value(), 22);
        }
        finally {
            $query = $this->cluster->makeQuery('DROP TABLE persons');
        }
    }
}

?>
