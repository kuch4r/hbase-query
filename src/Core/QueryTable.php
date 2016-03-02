<?php

/**
 * Created by PhpStorm.
 * User: kuchar
 * Date: 01.03.16
 * Time: 15:43
 */

namespace kuchar\HbaseQuery\Core;

use kuchar\HbaseQuery\Fields\Field;

abstract class QueryTable {
    protected $fieldsSet;
    protected $columnFamily;
    /**
     * Final constructor, use configure to set your own things
     */
    public function __construct() {
        $this->columnFamily = null;
        $this->setFieldSet( new FieldsSet() );

        $this->configure();
    }

    /**
     * Abstract method for setting fields
     * @return mixed
     */
    public abstract function configure();

    public function setFieldSet( FieldsSet $fieldSet ) {
        $fieldSet->setColumnFamily( $this->columnFamily );
        $this->fieldsSet = $fieldSet;
    }

    public function getFieldSet() {
        return $this->fieldsSet;
    }

    public function field( Field $field ) {
        $this->fieldsSet->addField($field);
    }

    public function fields( $fields ) {
        foreach( $fields as $field ) {
            $this->fieldsSet->addField($field);
        }
    }

    public function setColumnFamily( $family ) {
        $this->columnFamily = $family;
        $this->fieldsSet->setColumnFamily($family);
    }


    /**
     * executes query and returns hydrated results
     *
     * @param string $query - name of the query
     * @param array $params - params for the query
     */
    protected function query($query, $params = array(), $fields = null ) {
        /* getting */
        if( !method_exists( $this, $query) ) {
            throw new \ErrorException('Query "'.$query.'" doesn\'t exists');
        }
        /* performe query */
        $data = call_user_func(array($this,$query), $params);

        $newfs= new FieldsSet( $this->fieldsSet->getFields( $fields ) );

        $collection = new ResultCollection( $newfs );
        foreach( $data as $key => $row ) {
            $row['key'] = $key;
            $collection->add( $this->fieldsSet->clean($row, $fields) );
        }

        return $collection;
    }
}