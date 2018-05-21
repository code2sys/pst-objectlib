<?php

namespace PST;

use \PDO;
use \PDOException;


class LightspeedPartFactory extends AbstractFactory
{
    public function remove($id) {
        $obj = $this->get($id);
        parent::remove($id);

        // If this 
    }

    public function __construct($dbh, $master_factory, $obj = "PST\\LightspeedPartObject", $table = "lightspeedpart", $id = "lightspeedpart_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "part_number", "supplier_code", "description", "on_hand", "available", "on_order", "on_order_available", "last_sold", "last_received", "reorder_method", "min_qty", "max_qty", "cost", "current_active_price", "order_unit", "order_unit_qty", "last_count_date", "superseded_to", "upc", "bin1", "bin2", "bin3", "category", "lightspeed_last_seen", "uniqid", "lightspeed_present_flag", "retail", "partvariation_id", "eternalpartvariation_id"
        );
    }

}