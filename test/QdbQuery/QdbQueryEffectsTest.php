
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffectsTest extends QdbTestBase
{
    public function testFillTable()
    {
        $query = $this->cluster->makeQuery('CREATE TABLE persons(BLOB name INT64 age)');
        $this->assertTrue(empty($query->tables()));
        $this->assertEquals($query->scannedPointCount(), 0));

        $query = $this->cluster->makeQuery('INSERT INTO persons($timestamp, name, age)'
                                           'VALUES (now, Alice, 21), (now, Bob, 22)');
        $this->assertTrue(empty($query->tables()));
        $this->assertEquals($query->scannedPointCount(), 0));
        
        $query = $this->cluster->makeQuery('SELECT * FROM persons');
        $this->assertEquals(count($query->tables()), 1);
        $this->assertEquals($query->scannedPointCount(), 0));

        $table = $query->tables()[0];
        $this->assertEquals($table->table_name(), 'persons');
        $this->assertEquals($table->columns_names(), ['$timestamp', 'Alice', 'Bob']);
        $this->assertEquals($table->rows_count(), 2);
        $this->assertEquals($table->get_point(0, 0).type(), QdbQueryPoint::TIMESTAMP);
        $this->assertEquals($table->get_point(1, 0).type(), QdbQueryPoint::TIMESTAMP);
        $this->assertEquals($table->get_point(0, 1).type(), QdbQueryPoint::BLOB);
        $this->assertEquals($table->get_point(1, 1).type(), QdbQueryPoint::BLOB);
        $this->assertEquals($table->get_point(0, 2).type(), QdbQueryPoint::INT64);
        $this->assertEquals($table->get_point(1, 2).type(), QdbQueryPoint::INT64);
        $this->assertEquals($table->get_point(0, 1).value(), 'Alice');
        $this->assertEquals($table->get_point(1, 1).value(), 'Bob');
        $this->assertEquals($table->get_point(0, 2).value(), 21);
        $this->assertEquals($table->get_point(1, 2).value(), 22);

        $this->assertTrue(0);
    }
    
    public function testFail()
    {
        $this->assertTrue(0);
    }
}

?>
