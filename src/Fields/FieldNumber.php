<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;


class FieldNumber extends  Field {
    public function clean( $value ) {
        return intval($value);
    }
}