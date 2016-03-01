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
    /**
     * Field constructor.
     */
    public final function __construct( $name ) {
        $this->name = $name;
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
    public abstract function clean($value );

    public function getName() {
        return $this->name;
    }
}

class FieldCleanException extends \Exception {}