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

    public function setDealerInventory($partvariation_id, $amount, $cost, $price) {
        $stmt = $this->dbh->prepare("Insert into partdealervariation (partvariation_id, part_number, distributor_id, quantity_available, quantity_ten_plus, stock_code, quantity_last_updated, cost, price, clean_part_number) select partvariation_id, part_number, distributor_id, ?, ?, stock_code, now(), ?, ?, clean_part_number from partvariation where partvariation_id = ? on duplicate key update quantity_available = quantity_available + values(quantity_available), quantity_last_updated = now(), cost = values(cost), price = values(price)");
        $stmt->bindValue(1, $amount);
        $stmt->bindValue(2, $amount > 9 ? 1 : 0);
        $stmt->bindValue(3, $cost);
        $stmt->bindValue(4, $price);
        $stmt->bindValue(5, $partvariation_id);
        $stmt->execute();
    }
}
  