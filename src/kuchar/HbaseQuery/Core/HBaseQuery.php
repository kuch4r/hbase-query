<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.03.2016
 * Time: 16:26
 */

namespace kuchar\HbaseQuery\Core;


class HBaseQuery {
    protected $connection;
    protected $table;

    public function __construct( $connection, $table ) {
        $this->connection = $connection;
        $this->table      = $table;
    }

    /**
     * executes query and returns hydrated results
     *
     * @param string $query - name of the query
     * @param array $params - params for the query
     */
    public final function query($query, $params = array(), $fields = null ) {
        /* getting */
        if( !method_exists( $this, $query) ) {
            throw new \ErrorException('Query "'.$query.'" doesn\'t exists');
        }
        $fieldSet = $this->table->getFieldSet();

        /* performe query */
        $data = call_user_func( array($this,$query), $params,
                    $fieldSet->getQueryFields($fields) );

        /* simple query - just return array*/
        if( is_array($data) ) {
            return $fieldSet->clean($data,$fields);    
        } 
        
        $newfs = new FieldsSet( $fieldSet->getFields( $fields ) );

        $collection = new ResultCollection( $newfs );
        foreach( $data as $key => $row ) {
            $row['key'] = $key;
            $collection->add( $fieldSet->clean($row, $fields) );
        }

        return $collection;
    }
}
