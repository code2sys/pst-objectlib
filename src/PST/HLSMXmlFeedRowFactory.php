<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 2:18 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class HLSMXmlFeedRowFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\HLSMXmlFeedRowObject", $table = "hlsmxmlfeedrow", $id = "hlsmxmlfeed_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "hlsmxmlfeed_id", "number", "qty", "partnum", "hlsm_desc", "make", "hlsm_price", "hlsm_year", "hlsm_make", "hlsm_model", "hlsm_cat", "hlsm_dealer", "hlsm_showprice", "hlsm_ip_address", "partvariation_id", "uniqid"
        );
    }
}