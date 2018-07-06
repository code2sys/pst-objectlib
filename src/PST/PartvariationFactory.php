<?php
namespace PST;

use \PDO;
use \PDOException;


class PartvariationFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartvariationObject", $table = "partvariation", $id = "partvariation_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "part_number", "partnumber_id", "distributor_id", "quantity_available", "quantity_ten_plus", "stock_code", "created", "quantity_last_updated", "cost", "price", "weight", "clean_part_number", "width", "height", "length", "revisionset_id", "manufacturer_part_number", "processed", "uniqid", "zero_since_time", "massupdate", "closeout_on", "percentage", "bulk_insert_round", "ext_partvariation_id", "protect", "customerdistributor_id", "from_lightspeed"
        );
    }

    public function addDealerInventory($partvariation_id, $amount, $cost, $price) {
        $this->get($partvariation_id)->addDealerInventory($amount, $cost, $price);
    }
}
  