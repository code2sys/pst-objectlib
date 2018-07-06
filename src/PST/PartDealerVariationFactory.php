<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 4:55 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartDealerVariationFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartvariationObject", $table = "partvariation", $id = "partvariation_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "partvariation_id", "part_number", "partnumber_id", "distributor_id", "quantity_available",
            "quantity_ten_plus", "stock_code", "quantity_last_updated", "cost", "price",
            "weight", "clean_part_number", "width", "height", "length", "revisionset_id",
            "manufacturer_part_number", "zero_since_time", "massupdate", "closeout_on",
            "percentage", "processed", "uniqid"
        );
    }

}
