<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 3:01 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class OrderLightspeedShipmentFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderLightspeedShipmentObject", $table = "order_lightspeed_shipment", $id = "order_lightspeed_shipment_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_id", "lightspeed_date", "shipment_carrier", "shipment_method", "tracking_number", "item_id", "quantitiy"
        );
    }
}