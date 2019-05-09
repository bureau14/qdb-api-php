
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffectsTest extends QdbTestBase
{
    public function testFillTable()
    {
        $query = $this->cluster->makeQuery('CREATE TABLE persons(name BLOB, age INT64)');
        $this->assertEquals(0, count($query->tables()));
        $this->assertEquals(0, $query->scannedPointCount());

        $query = $this->cluster->makeQuery('INSERT INTO persons($timestamp, name, age)'.
                                           'VALUES (1970, "Alice", 21), (1970-01-01T00:00:01, "Bob", 22)');
        $this->assertEquals(0, count($query->tables()));
        $this->assertEquals(0, $query->scannedPointCount());
        
        $query = $this->cluster->makeQuery('SELECT * FROM persons');
        $this->assertEquals(1, count($query->tables()));
        $this->assertEquals(4, $query->scannedPointCount());

        $table = $query->tables()[0];
        echo '---- CREATED A '.get_class($table)."\n";

        $table->table_name();
        $this->assertEquals('persons',                      $table->table_name());
        $this->assertEquals(['timestamp', 'Alice', 'Bob', 'BREAK!'], $table->columns_names());
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
