<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;


class FieldFloat extends Field {
    public function clean( $value ) {
        return (float) $value;
    }
}