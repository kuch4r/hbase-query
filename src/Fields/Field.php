<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:00
 */

namespace kuchar\HbaseQuery\Fields;

abstract class Field  {
    protected $name;
    protected $family;
    protected $column;
    /**
     * Field constructor.
     */
    public final function __construct( $name, $column = null, $family = null ) {
        $this->name = $name;
        $this->family = $family;
        $this->column = is_null($column) ? $name : $column;
        $this->configure();
    }

    public function configure() {}


    /**
     * Clean value
     * Return value or assoc array of values
     *
     * @param $value
     * @return mixed
     */
    public function cleanValues( $values ) {
        return $this->clean( $values[ $this->getColumnName() ] );
    }

    abstract public function clean( $value );


    public function setFamily( $family ) {
        $this->family = $family;
    }

    public function getFamily() {
        return $this->family;
    }

    public function hasFamily() {
        return !is_null($this->family);
    }

    public function getName() {
        return $this->name;
    }

    public function getColumn() {
        return $this->column;
    }

    public function getColumnName() {
        /* row key is treated in special way (without column family) */
        if( $this->family == null && $this->column == 'key' ) {
            return $this->column;
        }
        if( is_null($this->family)) {
            throw new \ErrorException('Family cannot be null');
        }
        return $this->family.':'.$this->column;
    }
}

class FieldCleanException extends \Exception {}