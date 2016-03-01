<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;

class FieldString extends  Field {
    public function clean( $value ) {
        return $value;
    }
}