<?php

/**
 * Created by PhpStorm.
 * User: kuchar
 * Date: 01.03.16
 * Time: 15:43
 */

namespace kuchar\HbaseQuery\Core;

use kuchar\HbaseQuery\Fields\Field;

abstract class HBaseTable {
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




}