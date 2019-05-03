
<?php

require_once dirname(__FILE__).'/../QdbTestBase.php';

class QdbTsBatchTableStartRowTest extends QdbTestBase
{
    public function test()
    {
        $this->cluster->makeBatchTable([
            new QdbTsBatchColumnInfo('table', 'col1'),
            new QdbTsBatchColumnInfo('table', 'col2'),
            new QdbTsBatchColumnInfo('table', 'col3')
        ]);
    }

}

?>
