<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:25 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class ManufacturerFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ManufacturerObject", $table = "manufacturer", $id = "manufacturer_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "label", "name", "revisionset_id", "brand_id", "massupdate", "bulk_insert_round", "ext_manufacturer_id"
        );
    }
}