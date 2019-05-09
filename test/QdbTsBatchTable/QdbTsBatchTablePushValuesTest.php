
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTablePushValuesTest extends QdbTestBase
{
    public function testFillTable()
    {
        $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
        $this->assertEquals(count($query->tables()), 0);
        $this->assertEquals($query->scannedPointCount(), 0);

        $batch = $this->cluster->makeBatchTable([
            new QdbTsBatchColumnInfo('persons', 'name'),
            new QdbTsBatchColumnInfo('persons', 'age')
        ]);
        $batch->start_row(new QdbTimestamp(0, 0));
        $batch->set_blob(0, 'Alice');
        $batch->set_int64(1, 21);
        $batch->start_row(new QdbTimestamp(1, 0));
        $batch->set_blob(0, 'Bob');
        $batch->set_int64(1, 22);
        $batch->push_values();

        $query = $this->cluster->makeQuery('SELECT * FROM persons');
        $this->assertEquals(count($query->tables()), 1);
        $this->assertEquals($query->scannedPointCount(), 4);

        $table = $query->tables()[0];
        $this->assertEquals($table->table_name(),    'persons');
        $this->assertEquals($table->columns_names(), ['timestamp', 'name', 'age']);
        $this->assertEquals($table->rows_count(),    2);
        $this->assertEquals($table->points_rows()[0][0]->type(), QdbQueryPoint::TIMESTAMP);
        $this->assertEquals($table->points_rows()[1][0]->type(), QdbQueryPoint::TIMESTAMP);
        $this->assertEquals($table->points_rows()[0][1]->type(), QdbQueryPoint::BLOB);
        $this->assertEquals($table->points_rows()[1][1]->type(), QdbQueryPoint::BLOB);
        $this->assertEquals($table->points_rows()[0][2]->type(), QdbQueryPoint::INT64);
        $this->assertEquals($table->points_rows()[1][2]->type(), QdbQueryPoint::INT64);
        $this->assertEquals($table->points_rows()[0][0]->value(), new QdbTimestamp(0, 0));
        $this->assertEquals($table->points_rows()[1][0]->value(), new QdbTimestamp(1, 0));
        $this->assertEquals($table->points_rows()[0][1]->value(), 'Alice');
        $this->assertEquals($table->points_rows()[1][1]->value(), 'Bob');
        $this->assertEquals($table->points_rows()[0][2]->value(), 21);
        $this->assertEquals($table->points_rows()[1][2]->value(), 22);
        
        $query = $this->cluster->makeQuery('DROP TABLE persons');
        $this->assertEquals(count($query->tables()), 0);
        $this->assertEquals($query->scannedPointCount(), 0);
    }
}

?>
