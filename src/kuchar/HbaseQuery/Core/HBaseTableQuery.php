<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.03.2016
 * Time: 17:51
 */

namespace kuchar\HbaseQuery\Core;


abstract class HBaseTableQuery extends HBaseQuery{
    protected $table_name;

    public function __construct($connection, $table)
    {
        parent::__construct($connection, $table);
        $this->configure();
    }

    protected function setTableName( $name )
    {
        $this->table_name = $name;
    }
    
    public function getTableName()
    {
        return $this->table_name;
    }
    
    abstract function configure();

    public function row( $key, $fields = null )
    {
        return $this->query('queryRow', $key, $fields);
    }

    public function rows( $keys, $fields = null )
    {
        return $this->query('queryRows', $keys, $fields);
    }

    public function scanStartStop( $start, $stop = null, $fields = null, $limit = null )
    {
        return $this->query('queryScanStartStop', array(
            'start' => $start,
            'stop'  => $stop,
            'limit' => $limit
        ), $fields);
    }

    public function scanPrefix( $prefix, $fields = null, $limit = null )
    {
        return $this->query('queryScanPrefix', array(
            'prefix' => $prefix,
            'limit' => $limit
        ), $fields);
    }

    protected function queryRow( $params, $fields )
    {
        return $this->connection->table($this->table_name)->row( $params, $fields );
    }

    protected function queryRows( $params, $fields )
    {
        return $this->connection->table($this->table_name)->rows( $params, $fields );
    }

    protected function queryScanStartStop( $params, $fields )
    {
        if( !isset($params['start']) || !isset($params['stop'])) {
            throw new \Exception('Params must contains "start" and "stop" keys');
        }
        if( !isset($param['limit'])) {
            $params['limit'] = null;
        }
        return $this->connection->table($this->table_name)->scan( $params['start'],
            $params['stop'], null, $fields, null, 1000, $params['limit']);
    }

    protected function queryScanPrefix( $params, $fields )
    {
        if( !isset($params['prefix'])) {
            throw new \Exception('Params must contains "prefix" key');
        }
        if( !isset($param['limit'])) {
            $params['limit'] = null;
        }
        return $this->connection->table($this->table_name)->scan( null, null,
            $params['prefix'], $fields, null, 1000, $params['limit']);
    }
}
