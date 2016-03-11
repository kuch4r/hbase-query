<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;


class FieldHll extends  Field {
    public function clean( $value ) {
        $data = lz4_uncompress( $value );
        if( !$data ) {
            throw new FieldCleanException();
        }
        $obj = hllp_cnt_init($data,strlen($data)-3);
        if( !$obj ) {
            throw new FieldCleanException();
        }
        return $obj;
    }
}
