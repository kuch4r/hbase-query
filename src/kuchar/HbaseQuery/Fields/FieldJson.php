<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;

class FieldJson extends  Field {

    public function clean( $value ) {
        return json_decode($value);
    }
}