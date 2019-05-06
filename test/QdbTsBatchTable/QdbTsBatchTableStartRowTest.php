
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTableStartRowTest extends QdbTestBase
{
    public function testEmpty()
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
    }
}

?>
