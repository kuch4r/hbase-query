<?php

namespace kuchar\Tests\HbaseQuery;

use kuchar\smarthbase\SmartHConnection;
use PHPUnit\Framework\TestCase;
use kuchar\HbaseQuery\Core\HBaseTable;
use kuchar\HbaseQuery\Core\HBaseManager;
use kuchar\HbaseQuery\Core\HBaseTableQuery;
use kuchar\HbaseQuery\Fields\FieldString;

class HBaseTestTable extends HBaseTable {
    public function configure() {
        $this->setColumnFamily(THRIFT_TEST_CF);
        $this->fields( array(
            new FieldString('key', FieldString::KEY_COLUMN ),
            new FieldString('name'),
        ));
    }
}

class HBaseTestTableQuery extends HBaseTableQuery {
    public function configure() {
        $this->setTableName(THRIFT_TEST_TABLE);
    }
}

class HBaseManagerTest extends TestCase {
    public function setUp()
    {
        $connection = new SmartHConnection(THRIFT_TEST_URI);
        $connection->nativeCreateTable(THRIFT_TEST_TABLE, THRIFT_TEST_CF);

        $table = $connection->table(THRIFT_TEST_TABLE);
        $table->put('row1', [THRIFT_TEST_CF.':name' => 'val1']);
        $table->put('row2', [THRIFT_TEST_CF.':name' => 'val2']);
        $connection->close();
    }

    public function testCreate() {
        $manager = new HBaseManager(THRIFT_TEST_URI);

        $query = $manager->getQuery('kuchar\Tests\HbaseQuery\HBaseTestTable');
        $result = $query->rows(['row1', 'row2']);
        $data = $result->toAssocArray();

        $this->assertArrayHasKey('row1', $data);
        $this->assertArrayHasKey('name', $data['row1']);
        $this->assertEquals('val1', $data['row1']['name']);
    }
}