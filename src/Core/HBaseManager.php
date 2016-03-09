<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.03.2016
 * Time: 16:26
 */

namespace kuchar\HbaseQuery\Core;
use kuchar\smarthbase\SmartHConnection;

class HBaseManager {
    protected $tables;
    protected $hosts;
    protected $connections;

    public function __construct( $hosts = array() ) {
        $this->tables = array();
        $this->setHosts( $hosts );
    }

    public function setHosts( $hosts ) {
        if( !is_array($hosts) ) {
            $hosts = array($hosts);
        }

        $this->hosts  = $hosts;
    }

    public function addTables( array $tables ) {
        foreach( $tables as $table ) {
            $this->addTable( $table );
        }
    }

    public function addTable( $table ) {
        if( !class_exists($table) ) {
            throw new \Exception('Table class for this table doesn\'t exists: '.$table);
        }
        if( !is_subclass_of( $table, 'HBaseTable', true)) {
            //throw new \Exception($table.'Table is not subclass of HBaseTable');
        }
        $this->tables[$table] = new $table();
    }

    public function getQuery( $table ) {
        if( !isset($this->tables[$table])) {
            $this->addTable($table);
        }
        if( !class_exists($table.'Query') ) {
            throw new \Exception('Query class for this table doesn\'t exists: '.$table);
        }
        if( !is_subclass_of( $table.'Query', 'HBaseQuery', true)) {
            //throw new \Exception($table.'Query is not subclass of HBaseQuery');
        }
        $tablequery = $table.'Query';

        return new $tablequery( $this->getConnection(), $this->tables[$table]);
    }

    public function __destruct() {
        foreach( $this->connections as $key => $conn ) {
            $conn->close();
        }
    }

    protected function getConnection() {
        if( !count($this->hosts)) {
            throw new \Exception('Hosts array is empty');
        }
        return $this->getConnectionForHost($this->hosts[array_rand($this->hosts)]);
    }

    protected function getConnectionForHost( $host ) {
        if( !isset($this->connections[$host])) {
            list($h, $p) = explode(':',$host);
            $this->connections[$host] = new SmartHConnection( $h, $p );
        }
        return $this->connections[$host];
    }
}