<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 3:02 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class OrderProductFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderProductObject", $table = "order_product", $id = "order_product_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_id", "product_sku", "price", "qty", "distributor", "part_id", "status", "notes", "fitment", "dealer_qty", "lightspeed_partnumber", "lightspeed_shipped"
        );
    }

    public function introspectLightspeed() {
        $stmt = $this->dbh->prepare("update order_product join partnumber on order_product.product_sku = partnumber.partnumber join partvariation on partvariation.partnumber_id = partnumber.partnumber_id set order_product.lightspeed_partnumber = partvariation.part_number where order_product.lightspeed_partnumber is null or order_product.lightspeed_partnumber = '';");
        $stmt->execute();
    }
}