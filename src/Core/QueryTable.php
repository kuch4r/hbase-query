<?php

/**
 * Created by PhpStorm.
 * User: kuchar
 * Date: 01.03.16
 * Time: 15:43
 */

namespace kuchar\HbaseQuer\Core;

use kuchar\HbaseQuery\Core\FieldsSet;
use kuchar\HbaseQuery\Fields\Field;

abstract class QueryTable {
    protected $fieldsSet;
    /**
     * Final constructor, use configure to set your own things
     */
    public final function __constructor() {
        $this->fieldsSet = new FieldsSet();
        $this->configure();
    }

    /**
     * Abstract method for setting fields
     * @return mixed
     */
    public abstract function configure();

    public function field( Field $field ) {
        $this->fieldsSet->addField($field);
    }

    /**
     * executes query and returns hydrated results
     *
     * @param string $query - name of the query
     * @param array $params - params for the query
     */
    protected function query($query, $fields = null, $params ) {
        /* getting */
        if( !method_exists( $this, $query) ) {
            throw new \ErrorException('Query "'.$query.'" doesn\'t exists');
        }
        /* performe query */
        $data = call_user_func(array($this,$query), $params);

        foreach( $data as $key => $row ) {
            $row['key'] = $key;
            $cleanData = $this->fieldSet->clean($row, $fields);
        }

        return new ResultCollection( new FieldsSet( $this->fieldsSet->getFields( $fields ) ), $cleanData );
    }
}