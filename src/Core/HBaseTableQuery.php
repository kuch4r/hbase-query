<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.03.2016
 * Time: 17:51
 */

namespace kuchar\HbaseQuery\Core;


abstract class HBaseTableQuery extends HBaseQuery{
    protected $table;

    public function __construct($connection, $table)
    {
        parent::__construct($connection, $table);
        $this->configure();
    }

    protected function setTableName( $name ) {
        $this->table = $name;
    }

    abstract function configure();

    protected function row( $params, $fields ) {
        return $this->connection->table($this->table)->row( $params, $fields );
    }

    protected function rows( $params, $fields ) {
        return $this->connection->table($this->table)->rows( $params, $fields );
    }

    protected function scanStartStop( $params, $fields ) {
        if( !isset($params['start']) || !isset($params['stop'])) {
            throw new \Exception('Params must contains "start" and "stop" keys');
        }
        if( !isset($param['limit'])) {
            $params['limit'] = null;
        }
        return $this->connection->table($this->table)->scan( $params['start'],
            $params['stop'], null, $fields, null, 1000, $params['limit']);
    }

    protected function scanPrefix( $params, $fields ) {
        if( !isset($params['prefix'])) {
            throw new \Exception('Params must contains "prefix" key');
        }
        if( !isset($param['limit'])) {
            $params['limit'] = null;
        }
        return $this->connection->table($this->table)->scan( null, null,
            $params['prefix'], $fields, null, 1000, $params['limit']);
    }
}