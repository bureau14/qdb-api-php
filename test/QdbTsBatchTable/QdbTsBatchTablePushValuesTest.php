
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTablePushValuesTest extends QdbTestBase
{
    public function testFillTable()
    {
        $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
        $this->assertEquals(0, count($query->tables()));
        $this->assertEquals(0, $query->scannedPointCount());

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
        $this->assertEquals(1, count($query->tables()));
        $this->assertEquals(4, $query->scannedPointCount());

        $table = $query->tables()[0];
        $this->assertEquals('persons',                      $table->table_name());
        $this->assertEquals(['$timestamp', 'Alice', 'Bob'], $table->columns_names());
        $this->assertEquals(2,                              $table->rows_count());
        $this->assertEquals(QdbQueryPoint::TIMESTAMP, $table->get_point(0, 0).type());
        $this->assertEquals(QdbQueryPoint::TIMESTAMP, $table->get_point(1, 0).type());
        $this->assertEquals(QdbQueryPoint::BLOB,      $table->get_point(0, 1).type());
        $this->assertEquals(QdbQueryPoint::BLOB,      $table->get_point(1, 1).type());
        $this->assertEquals(QdbQueryPoint::INT64,     $table->get_point(0, 2).type());
        $this->assertEquals(QdbQueryPoint::INT64,     $table->get_point(1, 2).type());
        $this->assertEquals(new QdbTimestamp(0, 0), $table->get_point(0, 0).value());
        $this->assertEquals(new QdbTimestamp(1, 0), $table->get_point(1, 0).value());
        $this->assertEquals('Alice',                $table->get_point(0, 1).value());
        $this->assertEquals('Bob',                  $table->get_point(1, 1).value());
        $this->assertEquals(21,                     $table->get_point(0, 2).value());
        $this->assertEquals(22,                     $table->get_point(1, 2).value());
        
        $query = $this->cluster->makeQuery('DROP TABLE persons');
        $this->assertEquals(0, count($query->tables()));
        $this->assertEquals(0, $query->scannedPointCount());
    }
}

?>
