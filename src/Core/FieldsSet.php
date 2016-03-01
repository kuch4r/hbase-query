<?php

/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 15:57
 */

namespace kuchar\HbaseQuery\Core;

use kuchar\HbaseQuery\Fields;

class FieldsSet {
    protected $fields;
    protected $fields_order;
    /**
     * FieldsSet constructor.
     */
    public function __construct( $fields = array() ) {
        $this->fields = array();
        $this->fields_order = array();
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
        $this->fields[$field->getName()] = $field;
        $this->fields_order[] = $field->getName();
    }

    /**
     * Return generator with field objects
     *
     * @param $keys
     * @return \Generator
     */
    public function getFields( $keys = null ) {
        foreach( $this->fields_order as $name ) {
            if( !is_null($keys) && !in_array($name, $keys) ) {
                continue;
            }
            yield $this->fields[$name];
        }
    }

    /**
     * Clean values
     *
     * @param array $values
     * @return array
     * @throws \ErrorException
     */
    public function clean($values ) {
        $result = array();
        /* checking if we can clean all fields */
        foreach( array_keys($values) as $key ) {
            if( !isset($this->fields[$key])) {
                throw new \ErrorException('Field "'.$key.'" not part of FieldSet');
            }
        }
        /* cleaning fields */
        foreach( $this->fields_order as $name ) {
            if( !isset($values[$name])){
                continue;
            }
            $ret = $this->fields[$name]->clean( $values[$name]);
            if( is_array( $ret ) ) {
                foreach( $ret as $k => $v ) {
                    $result[$name.'#'.$k] = $v;
                }
            } else {
                $result[$name] = $ret;
            }
        }
        return $result;
    }
}