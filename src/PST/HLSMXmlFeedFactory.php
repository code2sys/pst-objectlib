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


class HLSMXmlFeedFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\HLSMXmlFeedObject", $table = "hlsmxmlfeed", $id = "hlsmxmlfeed_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "hlmsno", "item_count", "converted", "raw_xml", "claimed", "order_id"
        );
    }
}