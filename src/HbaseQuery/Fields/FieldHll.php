<?php
/**
 * Created by PhpStorm.
 * User: kucahr
 * Date: 01.03.16
 * Time: 16:09
 */

namespace kuchar\HbaseQuery\Fields;


class FieldHll extends Field {
    /**
     * @param $value
     * @return mixed
     * @throws FieldCleanException
     * @throws MissingHllModuleException
     */
    public function clean($value ) {
        if( class_exists('HyperLogLogPlusPlus')) {
            return $this->cleanNew($value);
        }
        if( function_exists('hllp_cnt_init')) {
            return $this->cleanOld($value);
        }

        throw new MissingHllModuleException();
    }

    /**
     * @param $value
     * @return mixed
     * @throws FieldCleanException
     */
    protected function cleanOld($value) {
        $data = lz4_uncompress( $value );
        if( !$data ) {
            throw new FieldCleanException();
        }
        $obj = hllp_cnt_init($data,strlen($data));
        if( !$obj ) {
            $obj = hllp_cnt_init($data,strlen($data)-3);
            if( !$obj ) {
                throw new FieldCleanException();
            }
        }

        return $obj;
    }


    /**
     * @param $value
     * @return \HyperLogLogPlusPlus
     * @throws FieldCleanException
     */
    protected function cleanNew($value) {
        $data = lz4_uncompress( $value );
        if( !$data ) {
            throw new FieldCleanException();
        }

        $obj = new \HyperLogLogPlusPlus(strlen($data),$data);
        if( !$obj ) {
            throw new FieldCleanException();
        }
        return $obj;
    }
}
