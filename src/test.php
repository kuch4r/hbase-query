<html><body><?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 15:33
 */

require_once('../../php-smart-hbase/src/SmartHConnection.php');
require_once('Core/HBaseTable.php');
require_once('Core/HBaseQuery.php');
require_once('Core/HBaseManager.php');
require_once('Core/FieldsSet.php');
require_once('Core/ResultCollection.php');
require_once ('Fields/Field.php');
require_once('Fields/FieldString.php');
require_once('Fields/FieldNumber.php');

use kuchar\smarthbase\SmartHConnection;
use kuchar\smarthbase\SmartHTable;
use kuchar\HbaseQuery\Core\HBaseTable;
use kuchar\HbaseQuery\Core\HBaseQuery;
use kuchar\HbaseQuery\Core\HBaseManager;
use kuchar\HbaseQuery\Fields\FieldString;
use kuchar\HbaseQuery\Fields\FieldNumber;

$c = new SmartHConnection('cdhn2.snrs.pl','9090');
$table = $c->table('sotrender_facebook_page');

/*foreach( $table->scan('100099',null, null,array('page'), null, 2 ,4  ) as $key => $item ) {
    var_dump($key);
    var_dump($item);
    echo "<br/>";
}*/

//var_dump( $table->row('100103120077551') );

//var_dump( $table->rows(array('100103120077551','100064773659')) );


class HBaseFacebookPostsTable extends HBaseTable {
    public function configure() {
        $this->setColumnFamily('page');
        $this->fields( array(
            new FieldString('name'),
            new FieldString('link'),
            new FieldNumber('likes')
        ));
    }
}

class HBaseFacebookPostsTableQuery extends HBaseQuery {
    public function queryBasic( $params ) {
        $table = $this->connection->table('sotrender_facebook_page');
        return $table->rows(array('100103120077551','100064773659'));
    }
}

$manager = new HBaseManager('cdhn1.snrs.pl:9090');
$q = $manager->getQuery('HBaseFacebookPostsTable');
$col = $q->query('queryBasic');

foreach( $col as $row ) {
    var_dump($row);
}
