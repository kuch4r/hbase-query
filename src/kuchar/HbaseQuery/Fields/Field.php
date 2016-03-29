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
    protected $keyColumn;

    const KEY_COLUMN = -1;

    /**
     * Field constructor.
     */
    public final function __construct( $name, $column = null, $family = null ) {
        $this->name = $name;
        if( $column === self::KEY_COLUMN ) {
            $this->family    = null;
            $this->keyColumn = true;
            $this->column    = 'key';
        } else {
            $this->keyColumn = false;
            $this->family = $family;
            $this->column = is_null($column) ? $name : $column;
        }
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
        return !$this->keyColumn && !is_null($this->family);
    }

    public function getName() {
        return $this->name;
    }

    public function getColumn() {
        return $this->column;
    }

    public function isKeyColumn() {
        return $this->keyColumn;
    }

    public function getColumnName() {
        /* row key is treated in special way (without column family) */
        if( $this->keyColumn ) {
            return $this->column;
        }

        if( is_null($this->family)) {
            throw new \ErrorException('Family cannot be null');
        }
        return $this->family.':'.$this->column;
    }
}

class FieldCleanException extends \Exception {}