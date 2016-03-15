<?php

/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 15:57
 */

namespace kuchar\HbaseQuery\Core;

use kuchar\HbaseQuery\Fields\Field;

class FieldsSet {
    protected $fields;
    protected $fieldsOrder;
    protected $defaultFamily;
    /**
     * FieldsSet constructor.
     */
    public function __construct( $fields = array(), $defaultFamily = null ) {
        $this->fields = array();
        $this->fieldsOrder = array();
        $this->defaultFamily = $defaultFamily;
        $this->addFields( $fields );
    }


    /**
     * Add fields to the class
     * @param $fields
     */
    public function addFields($fields ) {
        foreach( $fields as $field ) {
            $this->addField( $field );
        }
    }

    /**
     * Adds one field to the field sets
     *
     * @param Field $field
     * @throws \ErrorException
     */
    public function addField(Field $field ) {
        if( isset($this->fields[$field->getName()])) {
            throw new \ErrorException('Field "'.$field->getName().'" exists');
        }
        if( !$field->hasFamily() ) {
            $field->setFamily( $this->defaultFamily );
        }
        $this->fields[$field->getName()] = $field;
        $this->fieldsOrder[] = $field->getName();
    }

    /**
     * Returns true if field set contains given field
     *
     * @param $name
     * @return bool
     */
    public function hasField($name ) {
        return isset($this->fields[$name]);
    }

    /**
     * Return generator with field objects
     *
     * @param $keys - return only fields from the list
     * @return \Generator
     */
    public function getFields( $keys = null ) {
        foreach( $this->fieldsOrder as $name ) {
            if( !is_null($keys) && !in_array($name, $keys) ) {
                continue;
            }
            yield $this->fields[$name];
        }
    }

    public function getField( $key ) {
        return $this->fields[$key];
    }

    public function setColumnFamily( $name ) {
        if( is_null($name)) {
            return;
        }
        $this->defaultFamily = $name;
        foreach( $this->fields as $field ) {
            $field->setFamily( $name );
        }
    }

    /**
     * Clean values
     *
     * @param array $values
     * @return array
     * @throws \ErrorException
     */
    public function clean( $values, $selectFields = null ) {
        $result = array();
        /* checking if we can clean all fields */
        /*foreach( array_keys($values) as $key ) {
            if( !isset($this->fields[$key])) {
                throw new \ErrorException('Field "'.$key.'" not part of FieldSet');
            }
        }*/
        if( !is_null($selectFields) ) {
            $selectFields = array_flip($selectFields);
        }

        /* cleaning fields */
        foreach( $this->fieldsOrder as $name ) {
            $field = $this->fields[$name];
            if( !is_null( $selectFields ) && !isset( $selectFields[$name] ) ) {
                continue;
            }
            if( !isset($values[$field->getColumnName()])){
                continue;
            }

            $result[$field->getName()] = $field->cleanValues( $values );
        }
        return $result;
    }
}