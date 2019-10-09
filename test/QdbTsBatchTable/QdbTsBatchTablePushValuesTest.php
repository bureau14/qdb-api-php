
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTablePushValuesTest extends QdbTestBase
{
    public function testFillTable()
    {
        try {
            $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
            $this->assertEquals(count($query->tables()), 0);
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
            $this->assertEquals(count($query->tables()), 1);
            $this->assertEquals($query->scannedPointCount(), 4);

            $table = $query->tables()[0];
            $this->assertEquals($table->tableName(),    'persons');
            $this->assertEquals($table->columnsNames(), ['$timestamp', 'name', 'age']);
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
