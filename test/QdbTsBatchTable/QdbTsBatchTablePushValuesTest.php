
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTablePushValuesTest extends QdbTestBase
{
    public function testFillTable()
    {
        try {
            $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
            $this->assertEquals(count($query->columnNames()), 0);
            $this->assertEquals(count($query->rows()), 0);
            $this->assertEquals($query->scannedPointCount(), 0);

            $batch = $this->cluster->makeBatchTable([
                new QdbTsBatchColumnInfo('persons', 'name'),
                new QdbTsBatchColumnInfo('persons', 'age')
            ]);
            $batch->startRow(new QdbTimestamp(0, 0));
            $batch->setBlob(0, 'Alice');
            $batch->setInt64(1, 21);
            $batch->startRow(new QdbTimestamp(1, 0));
            $batch->setBlob(0, 'Bob');
            $batch->setInt64(1, 22);
            $batch->pushValues();

            $query = $this->cluster->makeQuery('SELECT * FROM persons');
            $this->assertEquals(count($query->columnNames()), 4);
            $this->assertEquals(count($query->rows()), 2);
            $this->assertEquals($query->scannedPointCount(), 4);

            $this->assertEquals($query->columnNames(), ['$timestamp', '$table', 'name', 'age']);
            $this->assertEquals($query->rows()[0][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->rows()[1][0]->type(), QdbQueryPoint::TIMESTAMP);
            $this->assertEquals($query->rows()[0][1]->type(), QdbQueryPoint::STRING);
            $this->assertEquals($query->rows()[1][1]->type(), QdbQueryPoint::STRING);
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
            $query = $this->cluster->makeQuery('DROP TABLE persons');
        }
    }
}

?>
