<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 17:47
 */

namespace kuchar\HbaseQuery\Core;


class ResultCollection implements  \Iterator {
    protected $fieldSet;
    protected $data;
    protected $position;

    /**
     * ResultCollection constructor.
     */
    public function __construct( FieldsSet $fieldSet ) {
        $this->fieldSet = $fieldSet;
        $this->data     = array();
        $this->position = 0;
    }

    public function add( $data ) {
        $this->data[] = $data;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->data[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position++;
    }

    public function valid() {
        return isset($this->data[$this->position]);
    }

    public function toArray() {
        return $this->data;
    }

    public function toAssocArray() {
        $result = array();
        foreach( $this->data as $row ){
            $result[$row['key']] = $row;
        }
        return $result;
    }


    public function generator() {
        foreach( $this->data as $d ){
            yield $d;
        }
    }

    public function exec( $type, $name ) {
        $field = $this->fieldSet->getField($name);
        $state = call_user_func( array($field,$type.'Init') );
        foreach( $this->data as $d ) {
            if( !isset($d[$name])) {
                continue;
            }
            call_user_func( array($field,$type), $d, $state );
        }
        return call_user_func( array($field,$type.'Result'), $state );
    }
}