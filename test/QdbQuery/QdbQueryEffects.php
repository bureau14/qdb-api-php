
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbQueryEffects extends QdbTestBase
{
    public function testFillTable()
    {
        $query = $this->cluster->makeQuery('CREATE TABLE persons(BLOB name INT64 age)');

        $this->assertTrue(empty($query->tables()));
        $this->assertEquals($query->scanned_point_count(), 0));
        
        $query = $this->cluster->makeQuery('INSERT INTO persons($timestamp, name, age) VALUES'
                                           '(now, Alice, 21), (now, Bob, 22)');

        $this->assertTrue(empty($query->tables()));
        $this->assertEquals($query->scanned_point_count(), 0));
        
        $query = $this->cluster->makeQuery('SELECT * FROM persons');

        $this->assertEquals(count($query->tables()), 1);
        $this->assertEquals($query->scanned_point_count(), 0));
    }
}

?>
