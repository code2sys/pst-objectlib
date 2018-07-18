<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 4:30 PM
 */



namespace PST;

use \PDO;
use \PDOException;


class OrderProductLightspeedActionFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderProductLightspeedActionObject", $table = "order_product_lightspeed_action", $id = "order_product_lightspeed_action_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_product_id", "action", "quantity", "lightspeed_date"
        );
    }

}